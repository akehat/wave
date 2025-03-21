import tkinter as tk
from tkinter import filedialog, messagebox, ttk
import os
import re
import json
from http.server import BaseHTTPRequestHandler, HTTPServer
from threading import Thread
import queue
import asyncio
import threading
import websockets
import base64
import subprocess
import platform
# [Text2 class remains unchanged]
class Text2:
    def __init__(self, root, height=10, width=50, wrap="word"):
        self.text = tk.Text(root, undo=True, height=height, width=width, wrap=wrap)
        self.text.pack(expand=True, fill="both")
        self.text.bind("<Control-a>", self.select_all)
        self.text.bind("<Control-A>", self.select_all)
        self.text.bind("<Control-c>", self.copy)
        self.text.bind("<Control-C>", self.copy)
        self.text.bind("<Control-x>", self.cut)
        self.text.bind("<Control-X>", self.cut)
        self.text.bind("<Control-v>", self.paste)
        self.text.bind("<Control-V>", self.paste)
        self.text.bind("<Control-z>", self.undo)
        self.text.bind("<Control-Z>", self.undo)
        self.text.bind("<Control-y>", self.redo)
        self.text.bind("<Control-Y>", self.redo)

    def select_all(self, event):
        self.text.tag_add("sel", "1.0", "end")
        return "break"

    def get_line_bounds(self):
        cursor_index = self.text.index("insert")
        line_number = int(cursor_index.split(".")[0])
        line_start = self.text.index(f"{line_number}.0")
        line_end = self.text.index(f"{line_number}.end")
        if line_number > 1:
            prev_line_end = self.text.index(f"{line_number - 1}.end")
            return prev_line_end, line_end
        return line_start, line_end

    def copy(self, event):
        try:
            selection = self.text.get("sel.first", "sel.last")
        except tk.TclError:
            start, end = self.get_line_bounds()
            selection = self.text.get(start, end)
        self.text.clipboard_clear()
        self.text.clipboard_append(selection)
        return "break"

    def cut(self, event):
        try:
            selection = self.text.get("sel.first", "sel.last")
            start = self.text.index("sel.first")
            end = self.text.index("sel.last")
        except tk.TclError:
            start, end = self.get_line_bounds()
            selection = self.text.get(start, end)
        self.text.clipboard_clear()
        self.text.clipboard_append(selection)
        self.text.delete(start, end)
        return "break"

    def paste(self, event):
        try:
            self.text.insert("insert", self.text.clipboard_get())
        except tk.TclError:
            pass
        return "break"

    def undo(self, event):
        try:
            self.text.edit_undo()
        except tk.TclError:
            pass
        return "break"

    def redo(self, event):
        try:
            self.text.edit_redo()
        except tk.TclError:
            pass
        return "break"

# [WebControllerApp class updated with new methods]
class WebControllerApp:
    def __init__(self, on_message_received):
        self.on_message_received = on_message_received
        self.connected_ws = None
        self.loop = None
        self.ws_thread = threading.Thread(target=self.start_websocket_server)
        self.ws_thread.daemon = False
        self.ws_thread.start()

    async def websocket_handler(self, websocket, path="test"):
        self.connected_ws = websocket
        print("Chrome extension connected")
        try:
            while True:
                message = await websocket.recv()
                print(f"Received from extension: {message}")
                self.on_message_received(message)
        except websockets.exceptions.ConnectionClosed:
            print("Chrome extension disconnected")
        finally:
            self.connected_ws = None

    async def run_server(self):
        async with websockets.serve(self.websocket_handler, "localhost", 8765):
            print("WebSocket server started on localhost:8765")
            await asyncio.Future()

    def start_websocket_server(self):
        loop = asyncio.new_event_loop()
        asyncio.set_event_loop(loop)
        self.loop = loop
        loop.run_until_complete(self.run_server())

    async def send_message(self, message):
        if self.connected_ws:
            await self.connected_ws.send(message)

    def send_command(self, command):
        if self.loop and self.connected_ws:
            message = json.dumps(command)
            asyncio.run_coroutine_threadsafe(self.send_message(message), self.loop)

    def set_text(self, text):
        self.send_command({"action": "set_text", "text": text})

    def set_file(self, file_path):
        with open(file_path, "rb") as f:
            file_content = f.read()
        base64_content = base64.b64encode(file_content).decode('utf-8')
        filename = os.path.basename(file_path)
        self.send_command({
            "action": "set_file",
            "base64": base64_content,
            "filename": filename,
            "mimeType": "text/plain"
        })

    def submit(self):
        self.send_command({"action": "submit"})

    def check_response(self):
        self.send_command({"action": "check"})

    def trigger_think(self):
        self.send_command({"action": "think"})  # New method for Think button

    def trigger_deepsearch(self):
        self.send_command({"action": "deepSearch"})  # New method for DeepSearch button

    def stop_websocket_server(self):
        if self.loop:
            self.loop.call_soon_threadsafe(self.loop.stop)

# [RequestHandler remains unchanged]
class RequestHandler(BaseHTTPRequestHandler):
    def do_POST(self):
        if self.path == '/upload':
            content_length = int(self.headers['Content-Length'])
            post_data = self.rfile.read(content_length)
            try:
                data = json.loads(post_data)
                commands = data['commands']
                self.server.app.command_queue.put(commands)
                self.send_response(200)
                self.send_header('Content-type', 'application/json')
                self.end_headers()
                response = json.dumps({"status": "success", "message": "Commands received"})
                self.wfile.write(response.encode('utf-8'))
            except Exception as e:
                self.send_response(400)
                self.send_header('Content-type', 'application/json')
                self.end_headers()
                response = json.dumps({"status": "error", "message": str(e)})
                self.wfile.write(response.encode('utf-8'))
        else:
            self.send_response(404)
            self.end_headers()

# Updated FileModifierApp with new buttons and improved layout
class FileModifierApp:
    def __init__(self, root):
        self.root = root
        self.root.title("File Modifier GUI")
        self.root.geometry("800x600")  # Set a default window size

        # Set default directory to "forAI"
        script_dir = os.path.dirname(os.path.abspath(__file__))
        self.txt_directory = os.path.join(script_dir, "forAI")
        if not os.path.exists(self.txt_directory):
            os.makedirs(self.txt_directory)
        
        self.txt_files = []
        self.file_mapping = {}
        info_path = os.path.join(self.txt_directory, 'info.json')
        if os.path.exists(info_path):
            with open(info_path, 'r', encoding='utf-8') as f:
                self.file_mapping = json.load(f)

        # Main layout with frames
        self.left_frame = tk.Frame(self.root)
        self.left_frame.pack(side=tk.LEFT, fill=tk.Y, padx=10, pady=10)

        self.right_frame = tk.Frame(self.root)
        self.right_frame.pack(side=tk.RIGHT, fill=tk.BOTH, expand=True, padx=10, pady=10)

        # Left frame: File Tree
        self.file_tree = ttk.Treeview(self.left_frame, columns=("File Name",), show="headings", height=20)
        self.file_tree.heading("File Name", text="File Name")
        self.file_tree.pack(fill=tk.Y)
        self.file_tree.bind("<Double-1>", self.open_file_viewer)

        # Right frame: Split into command and web control sections
        self.command_frame = tk.LabelFrame(self.right_frame, text="Commands", padx=10, pady=10)
        self.command_frame.pack(fill=tk.BOTH, expand=True)

        self.web_frame = tk.LabelFrame(self.right_frame, text="Web Controls", padx=10, pady=10)
        self.web_frame.pack(fill=tk.BOTH, expand=True)

        # Command Frame Layout
        self.select_btn = tk.Button(self.command_frame, text="Select Files", command=self.select_and_save_files)
        self.select_btn.pack(fill=tk.X, pady=2)

        self.commands_text = Text2(self.command_frame, height=5, width=50).text
        self.commands_text.pack(fill=tk.BOTH, expand=True, pady=2)

        self.button_frame = tk.Frame(self.command_frame)
        self.button_frame.pack(fill=tk.X, pady=2)
        self.apply_btn = tk.Button(self.button_frame, text="Apply Commands", command=self.apply_commands)
        self.apply_btn.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=2)
        self.add_numbers_btn = tk.Button(self.button_frame, text="Add Line Numbers", command=self.add_line_numbers_gui)
        self.add_numbers_btn.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=2)
        self.remove_numbers_btn = tk.Button(self.button_frame, text="Remove Line Numbers", command=self.remove_line_numbers_gui)
        self.remove_numbers_btn.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=2)
        self.push_back_btn = tk.Button(self.button_frame, text="Push Back", command=self.push_back)
        self.push_back_btn.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=2)

        # Web Control Frame Layout
        self.text_entry_label = tk.Label(self.web_frame, text="Set Webpage Text:")
        self.text_entry_label.pack(anchor="w", pady=2)
        self.text_entry = Text2(self.web_frame, height=5, width=50).text
        self.text_entry.pack(fill=tk.BOTH, expand=True, pady=2)

        self.web_button_frame = tk.Frame(self.web_frame)
        self.web_button_frame.pack(fill=tk.X, pady=2)
        self.set_text_btn = tk.Button(self.web_button_frame, text="Set Text", command=self.set_webpage_text)
        self.set_text_btn.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=2)
        self.upload_file_btn = tk.Button(self.web_button_frame, text="Upload File", command=self.upload_webpage_file)
        self.upload_file_btn.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=2)
        self.submit_btn = tk.Button(self.web_button_frame, text="Submit Form", command=self.submit_webpage_form)
        self.submit_btn.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=2)
        self.check_response_btn = tk.Button(self.web_button_frame, text="Check Response", command=self.check_response)
        self.check_response_btn.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=2)

        # New buttons for Think and DeepSearch
        self.think_btn = tk.Button(self.web_button_frame, text="Think", command=self.trigger_think)
        self.think_btn.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=2)
        self.deepsearch_btn = tk.Button(self.web_button_frame, text="DeepSearch", command=self.trigger_deepsearch)
        self.deepsearch_btn.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=2)

        # Initialize other components
        self.update_file_tree()
        self.command_queue = queue.Queue()
        self.root.after(100, self.process_queue)
        self.server = HTTPServer(('localhost', 8000), RequestHandler)
        self.server.app = self
        self.server_thread = Thread(target=self.server.serve_forever)
        self.server_thread.daemon = True
        self.server_thread.start()
        self.web_controller = WebControllerApp(self.handle_ws_message)
        self.root.protocol("WM_DELETE_WINDOW", self.on_closing)

    def handle_ws_message(self, message):
        self.root.after(0, self.process_ws_message, message)

    def process_ws_message(self, message):
        try:
            data = json.loads(message)
            if data['action'] == 'response':
                text_entry = self.text_entry.get("1.0", tk.END).strip()
                text = data['text']
                self.commands_text.insert(tk.END, text + '\n')
                messagebox.showinfo("Response", "Received response from AI")
            else:
                print(f"Unknown action: {data['action']}")
        except json.JSONDecodeError:
            print("Received invalid JSON message")

    def check_response(self):
        if self.web_controller:
            self.web_controller.check_response()

    def trigger_think(self):
        if self.web_controller:
            self.web_controller.trigger_think()
            messagebox.showinfo("Success", "Think action triggered")
        else:
            messagebox.showerror("Error", "WebSocket not available")

    def trigger_deepsearch(self):
        if self.web_controller:
            self.web_controller.trigger_deepsearch()
            messagebox.showinfo("Success", "DeepSearch action triggered")
        else:
            messagebox.showerror("Error", "WebSocket not available")

    def process_queue(self):
        try:
            while True:
                commands = self.command_queue.get_nowait()
                self.commands_text.delete("1.0", tk.END)
                self.commands_text.insert(tk.END, commands)
                self.apply_commands()
        except queue.Empty:
            pass
        self.root.after(100, self.process_queue)

    def open_file_viewer(self, event):
        selected_item = self.file_tree.selection()[0]
        file_name = self.file_tree.item(selected_item)['values'][0]
        file_path = os.path.join(self.txt_directory, file_name)
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        viewer_window = tk.Toplevel(self.root)
        viewer_window.title(file_name)
        frame = tk.Frame(viewer_window)
        frame.pack(expand=True, fill='both')
        
        text_widget = Text2(frame, wrap='none').text
        scrollbar = tk.Scrollbar(frame, orient='vertical', command=text_widget.yview)
        text_widget.config(yscrollcommand=scrollbar.set)
        text_widget.insert(tk.END, content)
        text_widget.pack(side='left', expand=True, fill='both')
        scrollbar.pack(side='right', fill='y')
        
        def save_changes():
            new_content = text_widget.get("1.0", tk.END)
            try:
                with open(file_path, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                messagebox.showinfo("Success", f"Saved changes to {file_name}")
                self.update_file_tree()
            except Exception as e:
                messagebox.showerror("Error", f"Failed to save {file_name}: {str(e)}")
        
        save_btn = tk.Button(viewer_window, text="Save", command=save_changes)
        save_btn.pack(pady=5)

    def update_file_tree(self):
        for item in self.file_tree.get_children():
            self.file_tree.delete(item)
        if os.path.exists(self.txt_directory):
            for file_name in os.listdir(self.txt_directory):
                if file_name.endswith(".txt"):
                    self.file_tree.insert("", "end", values=(file_name,))

    def select_and_save_files(self):
        file_paths = filedialog.askopenfilenames(
            title="Select .blade or .php Files",
            filetypes=[("Blade and PHP files", "*.*")]
        )
        if not file_paths:
            return
        self.txt_files = []
        for file_path in file_paths:
            file_name = os.path.splitext(os.path.basename(file_path))[0] + ".txt"
            txt_file_path = os.path.join(self.txt_directory, file_name)
            try:
                with open(file_path, 'r', encoding='utf-8') as src:
                    lines = src.readlines()
                numbered_lines = [f"{i+1}# {line}" for i, line in enumerate(lines)]
                with open(txt_file_path, 'w', encoding='utf-8') as dst:
                    dst.writelines(numbered_lines)
                self.txt_files.append(txt_file_path)
                self.file_mapping[file_name] = file_path
            except Exception as e:
                messagebox.showerror("Error", f"Failed to process {file_name}: {str(e)}")
        with open(os.path.join(self.txt_directory, 'info.json'), 'w', encoding='utf-8') as f:
            json.dump(self.file_mapping, f)
        self.update_file_tree()
        messagebox.showinfo("Success", f"Saved {len(file_paths)} files with line numbers")

    def apply_commands(self):
        """Apply commands from the text area to files or send data to the AI."""
        commands_text = self.commands_text.get("1.0", tk.END).strip()
        patterns = [
            # Existing patterns
            (r'FILE_MAKE\s+(\S+)\s*\*{4}\s*(.*?)\s*\*{4}', "MAKE"),
            (r'FILE_ADD\s+(\S+)\s+(\d+)\s*\*{4}\s*(.*?)\s*\*{4}', "ADD"),
            (r'FILE_REPLACE\s+(\S+)\s+(\d+)\s+(\d+)\s*\*{4}\s*(.*?)\s*\*{4}', "REPLACE"),
            # New patterns
            (r'GUI_PUSH_FILE\s+(\S+)', "PUSH_FILE"),
            (r'GUI_BASH\s*\*{4}\s*(.*?)\s*\*{4}', "BASH"),
            (r'GUI_TREE\s+(\S+)', "TREE"),
        ]
        all_matches = []
        for pattern, cmd_type in patterns:
            for match in re.finditer(pattern, commands_text, re.DOTALL):
                if cmd_type == "MAKE":
                    file_name, content = match.group(1), match.group(2)
                    all_matches.append({"type": cmd_type, "start": match.start(), "file_name": file_name, "content": content})
                elif cmd_type == "ADD":
                    file_name, line_num, content = match.group(1), int(match.group(2)), match.group(3)
                    all_matches.append({"type": cmd_type, "start": match.start(), "file_name": file_name, "line_num": line_num, "content": content})
                elif cmd_type == "REPLACE":
                    file_name, line_num, end_line, content = match.group(1), int(match.group(2)), int(match.group(3)), match.group(4)
                    all_matches.append({"type": cmd_type, "start": match.start(), "file_name": file_name, "line_num": line_num, "end_line": end_line, "content": content})
                elif cmd_type == "PUSH_FILE":
                    file_path = match.group(1)
                    all_matches.append({"type": "PUSH_FILE", "start": match.start(), "file_path": file_path})
                elif cmd_type == "BASH":
                    script = match.group(1).strip()
                    all_matches.append({"type": "BASH", "start": match.start(), "script": script})
                elif cmd_type == "TREE":
                    path = match.group(1)
                    all_matches.append({"type": "TREE", "start": match.start(), "path": path})
        
        # Sort matches by their starting position to process commands in order
        all_matches.sort(key=lambda x: x["start"])
        
        # Process each command
        for match in all_matches:
            try:
                if match["type"] == "MAKE":
                    file_path = os.path.join(self.txt_directory, match["file_name"])
                    content_lines = [line for line in match["content"].split('\n') if line.strip()]
                    self.make_file(file_path, content_lines)
                elif match["type"] == "ADD":
                    file_path = os.path.join(self.txt_directory, match["file_name"])
                    content_lines = [line for line in match["content"].split('\n') if line.strip()]
                    self.add_lines(file_path, match["line_num"], content_lines)
                elif match["type"] == "REPLACE":
                    file_path = os.path.join(self.txt_directory, match["file_name"])
                    content_lines = [line for line in match["content"].split('\n') if line.strip()]
                    self.replace_lines(file_path, match["line_num"], match["end_line"], content_lines)
                elif match["type"] == "PUSH_FILE":
                    self.push_file_to_ai(match["file_path"])
                elif match["type"] == "BASH":
                    self.execute_bash_and_send(match["script"])
                elif match["type"] == "TREE":
                    self.send_directory_tree(match["path"])
            except Exception as e:
                messagebox.showerror("Error", f"Error processing {match['type']}: {str(e)}")
                return
        
        self.update_file_tree()
        self.commands_text.delete("1.0", tk.END)
        messagebox.showinfo("Success", "Commands applied successfully")
    def make_file(self, file_path, content):
        numbered_lines = [f"{i+1}# {line}\n" for i, line in enumerate(content) if line.strip()]
        with open(file_path, 'w', encoding='utf-8') as f:
            f.writelines(numbered_lines)

    def add_lines(self, file_path, line_num, new_lines):
        with open(file_path, 'r', encoding='utf-8') as f:
            lines = f.readlines()
        insert_index = line_num - 1
        for i, new_line in enumerate(new_lines):
            lines.insert(insert_index + i, f"{line_num + i}# {new_line}\n")
        with open(file_path, 'w', encoding='utf-8') as f:
            f.writelines(lines)
        self.renumber_file(file_path)

    def replace_lines(self, file_path, start_line, end_line, new_lines):
        with open(file_path, 'r', encoding='utf-8') as f:
            lines = f.readlines()
        start_index = start_line - 1
        end_index = end_line - 1
        if start_index < 0 or end_index >= len(lines) or start_index > end_line:
            raise ValueError("Invalid line range")
        del lines[start_index:end_index + 1]
        for i, new_line in enumerate(new_lines):
            lines.insert(start_index + i, f"{start_line + i}# {new_line}\n")
        with open(file_path, 'w', encoding='utf-8') as f:
            f.writelines(lines)
        self.renumber_file(file_path)

    def renumber_file(self, file_path):
        with open(file_path, 'r', encoding='utf-8') as f:
            lines = f.readlines()
        content_lines = [re.sub(r'^\d+# ', '', line) for line in lines]
        numbered_lines = [f"{i+1}# {line}" for i, line in enumerate(content_lines)]
        with open(file_path, 'w', encoding='utf-8') as f:
            f.writelines(numbered_lines)

    def add_line_numbers_gui(self):
        files = filedialog.askopenfilenames(
            title="Select Files to Add Line Numbers",
            initialdir=self.txt_directory,
            filetypes=[("Text files", "*.txt")]
        )
        for file_path in files:
            with open(file_path, 'r', encoding='utf-8') as f:
                lines = f.readlines()
            numbered_lines = [f"{i+1}# {line}" for i, line in enumerate(lines)]
            with open(file_path, 'w', encoding='utf-8') as f:
                f.writelines(numbered_lines)
        self.update_file_tree()
        messagebox.showinfo("Success", "Line numbers added to selected files")

    def remove_line_numbers_gui(self):
        if not os.path.exists(self.txt_directory):
            messagebox.showerror("Error", "The 'forAI' directory does not exist")
            return
        for file_name in os.listdir(self.txt_directory):
            if file_name.endswith(".txt"):
                file_path = os.path.join(self.txt_directory, file_name)
                try:
                    with open(file_path, 'r', encoding='utf-8') as f:
                        lines = f.readlines()
                    cleaned_lines = [re.sub(r'^\d+# ', '', line) for line in lines]
                    with open(file_path, 'w', encoding='utf-8') as f:
                        f.writelines(cleaned_lines)
                except Exception as e:
                    messagebox.showerror("Error", f"Failed to process {file_name}: {str(e)}")
        self.update_file_tree()
        messagebox.showinfo("Success", "Line numbers removed from all .txt files")

    def push_back(self):
        if not self.file_mapping:
            messagebox.showinfo("Info", "No files to push back")
            return
        confirm = messagebox.askyesno("Confirm", "Are you sure you want to overwrite the original files?")
        if not confirm:
            return
        for txt_file, original_path in self.file_mapping.items():
            txt_path = os.path.join(self.txt_directory, txt_file)
            if os.path.exists(txt_path):
                try:
                    with open(txt_path, 'r', encoding='utf-8') as f:
                        lines = f.readlines()
                    content_lines = [re.sub(r'^\d+# ', '', line) for line in lines]
                    with open(original_path, 'w', encoding='utf-8') as f:
                        f.writelines(content_lines)
                except Exception as e:
                    messagebox.showerror("Error", f"Failed to push back {txt_file}: {str(e)}")
                    return
        messagebox.showinfo("Success", "Successfully pushed back to original files")

    def set_webpage_text(self):
        text = self.text_entry.get("1.0", tk.END).strip()
        if text and self.web_controller:
            self.web_controller.set_text(text)
            messagebox.showinfo("Success", "Text sent to webpage")
        else:
            messagebox.showerror("Error", "No text entered or WebSocket not available")

    def upload_webpage_file(self):
        file_path = filedialog.askopenfilename(
            title="Select File to Upload",
            filetypes=[("All files", "*.*")]
        )
        if file_path and self.web_controller:
            self.web_controller.set_file(file_path)
            messagebox.showinfo("Success", f"File {os.path.basename(file_path)} uploaded to webpage")
        else:
            messagebox.showerror("Error", "No file selected or WebSocket not available")

    def submit_webpage_form(self):
        if self.web_controller:
            self.web_controller.submit()
            messagebox.showinfo("Success", "Form submission triggered")
        else:
            messagebox.showerror("Error", "WebSocket not available")

    def on_closing(self):
        self.web_controller.stop_websocket_server()
        self.web_controller.ws_thread.join()
        self.root.destroy()
    def generate_tree(self, path, prefix=''):
        """Generate a string representation of the directory tree, OS-compatible."""
        if not os.path.exists(path):
            return f"Error: Path not found: {path}\n"
        os_name = platform.system().lower()
        try:
            if os_name == "windows":
                result = subprocess.run(f'tree "{path}" /f', shell=True, capture_output=True, text=True)
            elif os_name in ("linux", "darwin"):
                try:
                    result = subprocess.run(f'tree "{path}"', shell=True, capture_output=True, text=True, check=True)
                except subprocess.CalledProcessError:
                    result = subprocess.run(f'find "{path}" -type f', shell=True, capture_output=True, text=True)
            else:
                return f"Error: Unsupported OS: {os_name}\n"
            
            output = result.stdout
            if result.stderr:
                output += result.stderr
            if not output.strip():
                return f"Error: No output from tree command for {path}\n"
            return output
        except Exception as e:
            return f"Error generating tree for {path}: {str(e)}\n"

    def push_file_to_ai(self, file_path):
        """Save file with numbered lines in forAI folder and send to AI via WebSocket."""
        if not os.path.exists(file_path):
            messagebox.showerror("Error", f"File not found: {file_path}")
            return
        try:
            # Read the original file
            with open(file_path, 'r', encoding='utf-8') as f:
                lines = f.readlines()
            
            # Add numbered lines
            numbered_lines = [f"{i+1}# {line}" for i, line in enumerate(lines)]
            
            # Create .txt file in forAI folder
            file_name = os.path.splitext(os.path.basename(file_path))[0] + ".txt"
            txt_file_path = os.path.join(self.txt_directory, file_name)
            with open(txt_file_path, 'w', encoding='utf-8') as f:
                f.writelines(numbered_lines)
            
            # Update file mapping for push-back functionality
            self.file_mapping[file_name] = file_path
            with open(os.path.join(self.txt_directory, 'info.json'), 'w', encoding='utf-8') as f:
                json.dump(self.file_mapping, f)
            
            # Send the numbered content to AI
            text_content = ''.join(numbered_lines)
            self.web_controller.set_text(text_content)
            self.web_controller.submit()
            
            # Refresh the file tree
            self.update_file_tree()
            messagebox.showinfo("Success", f"File saved as {file_name} and sent to AI")
        except Exception as e:
            messagebox.showerror("Error", f"Failed to process and send {file_path}: {str(e)}")

    def send_directory_tree(self, path):
        """Send the directory tree of the specified path to the AI."""
        if not os.path.exists(path):
            messagebox.showerror("Error", f"Path not found: {path}")
            return
        tree_str = self.generate_tree(path)
        self.web_controller.set_text(tree_str)
        self.web_controller.submit()  
if __name__ == "__main__":
    root = tk.Tk()
    app = FileModifierApp(root)
    root.mainloop()

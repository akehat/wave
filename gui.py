import tkinter as tk
from tkinter import filedialog, messagebox
import os

class FileModifierApp:
    def __init__(self, root):
        self.root = root
        self.root.title("File Modifier GUI")

        # Directory where .txt files will be saved
        self.txt_directory = None
        # List of saved .txt file paths
        self.txt_files = []

        # GUI Components
        # Button to select files
        self.select_btn = tk.Button(root, text="Select .blade/.php Files", command=self.select_and_save_files)
        self.select_btn.pack(pady=10)

        # Text area for commands
        self.commands_text = tk.Text(root, height=10, width=50)
        self.commands_text.pack(pady=10)
        self.commands_text.insert(tk.END, "# Example commands:\n# modify file1.txt 3 \"New text here\"\n# delete file2.txt 5\n# add file1.txt 10 \"Added line\"")

        # Button to apply commands
        self.apply_btn = tk.Button(root, text="Apply Commands", command=self.apply_commands)
        self.apply_btn.pack(pady=5)

        # Button to generate script
        self.generate_btn = tk.Button(root, text="Generate Modification Script", command=self.generate_script)
        self.generate_btn.pack(pady=5)

    def select_and_save_files(self):
        """Select .blade or .php files and save them as .txt in a chosen directory."""
        file_paths = filedialog.askopenfilenames(
            title="Select .blade or .php Files",
            filetypes=[("Blade and PHP files", "*.php")]
        )
        if not file_paths:
            return

        self.txt_directory = filedialog.askdirectory(title="Select Directory to Save .txt Files")
        if not self.txt_directory:
            return

        self.txt_files = []
        for file_path in file_paths:
            file_name = os.path.basename(file_path)
            txt_file_name = os.path.splitext(file_name)[0] + ".txt"
            txt_file_path = os.path.join(self.txt_directory, txt_file_name)
            try:
                with open(file_path, 'r', encoding='utf-8') as src, open(txt_file_path, 'w', encoding='utf-8') as dst:
                    dst.write(src.read())
                self.txt_files.append(txt_file_path)
            except Exception as e:
                messagebox.showerror("Error", f"Failed to process {file_name}: {str(e)}")
        messagebox.showinfo("Success", f"Saved {len(file_paths)} files as .txt in {self.txt_directory}")

    def apply_commands(self):
        """Apply the commands entered in the text area to the .txt files."""
        commands = self.commands_text.get("1.0", tk.END).strip().split('\n')
        for command in commands:
            if command.strip().startswith('#') or not command.strip():
                continue  # Skip comments or empty lines
            parts = command.split(maxsplit=3)
            if len(parts) < 2:
                messagebox.showerror("Error", f"Invalid command: {command}")
                continue

            action, file_name = parts[0], parts[1]
            file_path = os.path.join(self.txt_directory, file_name)
            if not os.path.exists(file_path):
                messagebox.showerror("Error", f"File not found: {file_path}")
                continue

            try:
                if action == "modify" and len(parts) == 4:
                    line_num = int(parts[2])
                    new_text = parts[3].strip('"')  # Remove quotes if present
                    self.modify_line(file_path, line_num, new_text)
                elif action == "delete" and len(parts) == 3:
                    line_num = int(parts[2])
                    self.delete_line(file_path, line_num)
                elif action == "add" and len(parts) == 4:
                    line_num = int(parts[2])
                    text = parts[3].strip('"')
                    self.add_line(file_path, line_num, text)
                else:
                    messagebox.showerror("Error", f"Invalid command format: {command}")
            except ValueError:
                messagebox.showerror("Error", f"Invalid line number in: {command}")
            except Exception as e:
                messagebox.showerror("Error", f"Error processing {command}: {str(e)}")
        messagebox.showinfo("Success", "Commands applied to .txt files")

    def modify_line(self, file_path, line_num, new_text):
        """Modify a specific line in the file."""
        with open(file_path, 'r', encoding='utf-8') as file:
            lines = file.readlines()
        if 1 <= line_num <= len(lines):
            lines[line_num - 1] = new_text + '\n'
            with open(file_path, 'w', encoding='utf-8') as file:
                file.writelines(lines)
        else:
            raise ValueError(f"Line number {line_num} out of range")

    def delete_line(self, file_path, line_num):
        """Delete a specific line from the file."""
        with open(file_path, 'r', encoding='utf-8') as file:
            lines = file.readlines()
        if 1 <= line_num <= len(lines):
            del lines[line_num - 1]
            with open(file_path, 'w', encoding='utf-8') as file:
                file.writelines(lines)
        else:
            raise ValueError(f"Line number {line_num} out of range")

    def add_line(self, file_path, line_num, text):
        """Add a new line at a specific position."""
        with open(file_path, 'r', encoding='utf-8') as file:
            lines = file.readlines()
        if 1 <= line_num <= len(lines) + 1:
            lines.insert(line_num - 1, text + '\n')
            with open(file_path, 'w', encoding='utf-8') as file:
                file.writelines(lines)
        else:
            raise ValueError(f"Line number {line_num} out of range")

    def generate_script(self):
        """Generate a Python script that applies all the commands."""
        commands = self.commands_text.get("1.0", tk.END).strip().split('\n')
        script = "import os\n\n"
        script += "def modify_file(file_path, modifications):\n"
        script += "    with open(file_path, 'r', encoding='utf-8') as file:\n"
        script += "        lines = file.readlines()\n"
        script += "    for mod in modifications:\n"
        script += "        if mod['action'] == 'modify':\n"
        script += "            lines[mod['line'] - 1] = mod['text'] + '\\n'\n"
        script += "        elif mod['action'] == 'delete':\n"
        script += "            del lines[mod['line'] - 1]\n"
        script += "        elif mod['action'] == 'add':\n"
        script += "            lines.insert(mod['line'] - 1, mod['text'] + '\\n')\n"
        script += "    with open(file_path, 'w', encoding='utf-8') as file:\n"
        script += "        file.writelines(lines)\n\n"

        # Organize modifications by file
        modifications = {}
        for command in commands:
            if command.strip().startswith('#') or not command.strip():
                continue
            parts = command.split(maxsplit=3)
            if len(parts) < 2:
                continue
            action, file_name = parts[0], parts[1]
            if file_name not in modifications:
                modifications[file_name] = []
            if action == "modify" and len(parts) == 4:
                line_num = int(parts[2])
                text = parts[3].strip('"')
                modifications[file_name].append({'action': 'modify', 'line': line_num, 'text': text})
            elif action == "delete" and len(parts) == 3:
                line_num = int(parts[2])
                modifications[file_name].append({'action': 'delete', 'line': line_num})
            elif action == "add" and len(parts) == 4:
                line_num = int(parts[2])
                text = parts[3].strip('"')
                modifications[file_name].append({'action': 'add', 'line': line_num, 'text': text})

        # Add modifications to the script
        for file_name, mods in modifications.items():
            var_name = file_name.replace('.', '_')
            script += f"modifications_{var_name} = [\n"
            for mod in mods:
                if mod['action'] in ['modify', 'add']:
                    script += f"    {{'action': '{mod['action']}', 'line': {mod['line']}, 'text': '{mod['text']}'}},\n"
                else:
                    script += f"    {{'action': '{mod['action']}', 'line': {mod['line']}}},\n"
            script += "]\n"
            script += f"modify_file(os.path.join('{self.txt_directory}', '{file_name}'), modifications_{var_name})\n\n"

        # Save the script
        script_path = os.path.join(self.txt_directory, 'modify_files.py')
        with open(script_path, 'w', encoding='utf-8') as file:
            file.write(script)
        messagebox.showinfo("Success", f"Script saved at {script_path}\nRun it to apply all modifications!")

if __name__ == "__main__":
    root = tk.Tk()
    app = FileModifierApp(root)
    root.mainloop()
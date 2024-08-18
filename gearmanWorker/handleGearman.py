import asyncio
import os
import sys
import traceback
import json
from datetime import datetime

try:
    import python3_gearman
    from dotenv import load_dotenv

    # Custom API libraries
    from chaseAPI import *
    from fennelAPI import *
    from fidelityAPI import *
    from firstradeAPI import *
    from helperAPI import (
        ThreadHandler,
        check_package_versions,
        printAndDiscord,
        stockOrder,
        updater,
    )
    from publicAPI import *
    from robinhoodAPI import *
    from schwabAPI import *
    from tastyAPI import *
    from tradierAPI import *
    from vanguardAPI import *
    from webullAPI import *
except Exception as e:
    print(f"Error importing libraries: {e}")
    print(traceback.format_exc())
    sys.exit(1)

# Override the print function to log output to both console and file
def custom_print(*args, **kwargs):
    original_print(*args, **kwargs)
    log_filename = datetime.now().strftime("%Y-%m-%d-s.log")
    with open(log_filename, 'a+') as log_file:
        log_file.write(" ".join(map(str, args)) + "\n")

# Replace the default print function
original_print = print
print = custom_print

# Initialize .env file
load_dotenv()

# Modified fun_run to collect results into a JSON-encoded object
def fun_run(orderObj: stockOrder, command, botObj=None, loop=None):
    results = {}
    if command in [("_init", "_holdings"), ("_init", "_transaction")]:
        for broker in orderObj.get_brokers():
            if broker in orderObj.get_notbrokers():
                continue
            broker = nicknames(broker)
            first_command, second_command = command
            try:
                fun_name = broker + first_command
                if broker.lower() == "fidelity":
                    orderObj.set_logged_in(
                        globals()[fun_name](DOCKER=DOCKER_MODE, botObj=botObj, loop=loop), broker
                    )
                elif broker.lower() in ["fennel", "public"]:
                    orderObj.set_logged_in(
                        globals()[fun_name](botObj=botObj, loop=loop), broker
                    )
                elif broker.lower() in ["chase", "vanguard"]:
                    fun_name = broker + "_run"
                    th = ThreadHandler(
                        globals()[fun_name],
                        orderObj=orderObj,
                        command=command,
                        botObj=botObj,
                        loop=loop,
                    )
                    th.start()
                    th.join()
                    result, err = th.get_result()
                    if err is not None:
                        raise Exception(
                            "Error in "
                            + fun_name
                            + ": Function did not complete successfully."
                        )
                    results[broker] = result
                else:
                    orderObj.set_logged_in(globals()[fun_name](), broker)
                
                if broker.lower() not in ["chase", "vanguard"]:
                    orderObj.order_validate(preLogin=False)
                    logged_in_broker = orderObj.get_logged_in(broker)
                    if logged_in_broker is None:
                        results[broker] = "Error: Not logged in, skipping..."
                        continue

                    if second_command == "_holdings":
                        fun_name = broker + second_command
                        holdings = globals()[fun_name](logged_in_broker, loop)
                        results[broker] = holdings
                    elif second_command == "_transaction":
                        fun_name = broker + second_command
                        transaction_result = globals()[fun_name](
                            logged_in_broker,
                            orderObj,
                            loop,
                        )
                        results[broker] = transaction_result
            except Exception as ex:
                results[broker] = {
                    "error": str(ex),
                    "traceback": traceback.format_exc()
                }
    else:
        results["error"] = f"{command} is not a valid command"
    
    return json.dumps(results)

# Gearman worker function with JSON return
def gearman_worker_function(gearman_worker, gearman_job):
    try:
        data = json.loads(gearman_job.data)
        # Override environment variables with data from client
        for key, value in data.get("env", {}).items():
            os.environ[key] = value
        args = data.get("args", [])
        
        if "discord" in args:
            updater()
            check_package_versions()
            print("Running Discord bot from command line")
            # Here you can include your Discord bot code if needed
        else:
            updater()
            check_package_versions()
            cliOrderObj = argParser(args)
            if cliOrderObj.get_holdings():
                result = fun_run(cliOrderObj, ("_init", "_holdings"))
            else:
                result = fun_run(cliOrderObj, ("_init", "_transaction"))
    except Exception as e:
        print(f"Error processing gearman job: {e}")
        print(traceback.format_exc())
        return json.dumps({"status": "error", "message": str(e)})
    return json.dumps({"status": "success", "data": result})

# Starting the Gearman worker
if __name__ == "__main__":
    gearman_worker = python3_gearman.GearmanWorker(["localhost:4730"])
    gearman_worker.register_task("execute_command", gearman_worker_function)
    print("Gearman worker started and awaiting jobs...")
    gearman_worker.work()

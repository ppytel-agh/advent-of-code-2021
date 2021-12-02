import math

inputFileName = "input"
fileHandle = open(inputFileName, "r")
inputLines = fileHandle.readlines()
depth = 0
horizontal = 0
for inputLine in inputLines:
    action, valueString = inputLine.split(' ')    
    actionValue = int(valueString)
    if action == "forward":
        horizontal += actionValue
    elif action == "down":
        depth += actionValue
    elif action == "up":
        depth -= actionValue
    else:
        print("undefined action")


multiplied = horizontal * depth
print("horizontal: " + str(horizontal) + "; depth: " + str(depth) + "; multiplied: " + str(multiplied))

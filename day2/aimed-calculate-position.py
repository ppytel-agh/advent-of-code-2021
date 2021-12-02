import math

def calculateAimedPosition(inputFileName):
    fileHandle = open(inputFileName, "r")
    inputLines = fileHandle.readlines()
    depth = 0
    horizontal = 0
    aim = 0
    for inputLine in inputLines:
        action, valueString = inputLine.split(' ')    
        actionValue = int(valueString)
        if action == "forward":
            horizontal += actionValue
            depth += aim * actionValue
        elif action == "down":
            aim += actionValue
        elif action == "up":
            aim -= actionValue
        else:
            print("undefined action")
    return (horizontal, depth)

testValues = calculateAimedPosition("test-input.txt")
if testValues == (15, 60):
    print("horizontal: " + str(testValues[0]) + "; depth: " + str(testValues[1]) + "; multiplied: " + str(testValues[0] * testValues[1]))
    print("test passed")
    values = calculateAimedPosition("input")
    multiplied = values[0] * values[1]
    print("horizontal: " + str(values[0]) + "; depth: " + str(values[1]) + "; multiplied: " + str(multiplied))
    print(multiplied)
else:
    print("test failed!")
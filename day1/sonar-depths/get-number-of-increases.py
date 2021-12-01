
inputFileName = "input"
fileHandle = open(inputFileName, "r")
inputLines = fileHandle.readlines()
depths = [int(depthString) for depthString in inputLines]
previousDepth = max(depths) + 1
depthIncreases = 0
for depth in depths:
    if depth > previousDepth:
        depthIncreases += 1
    previousDepth = depth
print(depthIncreases)

inputFileName = "input"
fileHandle = open(inputFileName, "r")
inputLines = fileHandle.readlines()
depths = [int(depthString) for depthString in inputLines]
previousWindowDepth = 3 * max(depths)
depthIncreases = 0
depthsCount = len(depths)
for depthId in range(depthsCount - 2):
    depthsWindow = depths[depthId] + depths[depthId + 1] + depths[depthId + 2]
    if depthsWindow > previousWindowDepth:
        depthIncreases += 1
    previousWindowDepth = depthsWindow
print(depthIncreases)
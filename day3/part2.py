def calculateRatio(valuesArray, bitIndex):
    sum = 0
    for binaryValue in valuesArray:
        sum += int(binaryValue[bitIndex])
    ratio = float(sum) / float(len(valuesArray))
    return ratio

def remainValuesWithBit(valuesArray, bitIndex, bitValue):
    newValues = []
    for value in valuesArray:
        if value[bitIndex] == bitValue:
            newValues.append(value)
    return newValues

def calculateLifeSupportRating(inputFilename, verbose = False):
    fileHandle = open(inputFilename, 'r')
    inputLines = fileHandle.readlines()
    inputSize = len(inputLines)
    if(verbose):
        print("inputSize")
        print(inputSize)
    numberOfBitsInValues = len(inputLines[0]) - 1
    if(verbose):
        print("numberOfBitsInValues")
        print(numberOfBitsInValues)
    oxygenGeneratorNumbers = inputLines
    co2ScrubberNumbers = inputLines
    for bitIndex in range(numberOfBitsInValues):
        if(verbose):
            print("bitIndex")
            print(bitIndex)
        if(len(oxygenGeneratorNumbers) > 1):
            oxygenRatio = calculateRatio(oxygenGeneratorNumbers, bitIndex)
            bitToRemain = '1' if (oxygenRatio >= 0.5) else '0'
            oxygenGeneratorNumbers = remainValuesWithBit(oxygenGeneratorNumbers, bitIndex, bitToRemain)
        if(len(co2ScrubberNumbers) > 1):
            co2Ratio = calculateRatio(co2ScrubberNumbers, bitIndex)
            bitToRemain = '0' if (co2Ratio >= 0.5) else '1'
            co2ScrubberNumbers = remainValuesWithBit(co2ScrubberNumbers, bitIndex, bitToRemain)        
    if(len(oxygenGeneratorNumbers) > 1):
        print("not enough filtering of oxygen numbers")
    if(len(co2ScrubberNumbers) > 1):  
        print("not enough filtering of co2 numbers")
    if verbose:
        print(oxygenGeneratorNumbers)
        print(co2ScrubberNumbers)
    oxygenGeneratorRating = 0
    co2ScrubRating = 0
    for bitIndex in range(numberOfBitsInValues):
        bitValue = 2**(numberOfBitsInValues - bitIndex - 1)
        oxygenGeneratorRating += int(oxygenGeneratorNumbers[0][bitIndex]) * bitValue
        co2ScrubRating += int(co2ScrubberNumbers[0][bitIndex]) * bitValue
    lifeSupportRating = oxygenGeneratorRating * co2ScrubRating
    return (oxygenGeneratorRating, co2ScrubRating, lifeSupportRating)

oxygenGeneratorRating, co2ScrubRating, lifeSupportRating = calculateLifeSupportRating("test-input.txt", True)
print("TEST oxygen, co2, lifeSupport")
print(oxygenGeneratorRating)
print(co2ScrubRating)
print(lifeSupportRating)
if oxygenGeneratorRating == 23 and co2ScrubRating == 10 and lifeSupportRating == 230:
    print("test passed")
    oxygenGeneratorRating, co2ScrubRating, lifeSupportRating = calculateLifeSupportRating("input.txt")
    print("oxygen, co2, lifeSupport")
    print(oxygenGeneratorRating)
    print(co2ScrubRating)
    print(lifeSupportRating)    
else:
    print("test failed")
    
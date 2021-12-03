def calculateGammaAndEpsilon(inputFilename, verbose = False):
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
    gammaRate = 0
    epsilonRate = 0
    for bitIndex in range(numberOfBitsInValues):
        if(verbose):
            print("bitIndex")
            print(bitIndex)
        sum = 0
        if(verbose):
            print("inputLines")
        for binaryValue in inputLines:
            if(verbose):
                print(binaryValue[bitIndex])
            sum += int(binaryValue[bitIndex])
        ratio = float(sum) / float(inputSize)
        if(verbose):
            print("ratio")
            print(ratio)
        bitValue = 2**(numberOfBitsInValues - bitIndex - 1)
        if(verbose):
            print("bitValue")
            print(bitValue)
        if ratio > 0.5:
            gammaRate += bitValue
        elif ratio < 0.5:
            epsilonRate += bitValue
        else:
            print("equal number of ones and zeroes")
    product = gammaRate * epsilonRate    
    return (gammaRate, epsilonRate, product)

testGamma, testEpsilon, testProduct = calculateGammaAndEpsilon("test-input.txt")
print("TEST gamma, epsilon, product")
print(testGamma)
print(testEpsilon)
print(testProduct)
if testGamma == 22 and testEpsilon == 9 and testProduct == 198:
    print("test passed")
    gamma, epsilon, product =  calculateGammaAndEpsilon("input.txt")
    print("gamma, epsilon, product")
    print(gamma)
    print(epsilon)
    print(product)
else:
    print("test failed")
    
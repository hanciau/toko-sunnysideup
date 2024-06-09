<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Continuous Combinations</title>
</head>
<body>
    <h1>Continuous Combinations</h1>
    <button onclick="generateAndDisplay()">Generate and Display</button>
    <ul id="resultList"></ul>

    <script>
        var combinations = [[1, 1, 1, 1, 1, 1]];

        function generateAndDisplay() {
            var resultList = document.getElementById("resultList");

            // Generate a new combination
            var newCombination = incrementCombination(combinations[combinations.length - 1].slice());
            combinations.push(newCombination);

            // Display the new combination
            var listItem = document.createElement("li");
            listItem.appendChild(document.createTextNode(newCombination.join('')));
            resultList.appendChild(listItem);
        }

        function incrementCombination(combination) {
            for (var i = combination.length - 1; i >= 0; i--) {
                if (combination[i] < 9) {
                    combination[i]++;
                    break;
                } else {
                    combination[i] = 1;
                }
            }
            return combination;
        }
    </script>
</body>
</html>

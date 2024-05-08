<?php
session_start();

function factorial($n)
{
    if ($n == 0) {
        return 1;
    } else {
        return $n * factorial($n - 1);
    }
}

function addToHistory($entry) {
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = array();
    }
    $_SESSION['history'][] = $entry;
}

function saveToMemory($num1, $num2, $operation) {
    $_SESSION['memory'] = array(
        'num1' => $num1,
        'num2' => $num2,
        'operation' => $operation
    );
}

function retrieveFromMemory() {
    if (isset($_SESSION['memory'])) {
        return $_SESSION['memory'];
    } else {
        return false;
    }
}

function clearMemory() {
    unset($_SESSION['memory']);
}

if (isset($_POST['calculate'])) {
    $num1 = $_POST['num1'];
    $num2 = $_POST['num2'];
    $operation = $_POST['operation'];
    $result = '';

    switch ($operation) {
        case '+':
            $result = $num1 + $num2;
            break;
        case '-':
            $result = $num1 - $num2;
            break;
        case '*':
            $result = $num1 * $num2;
            break;
        case '/':
            if ($num2 != 0) {
                $result = $num1 / $num2;
            } else {
                $result = "Erro: Divisão por zero!";
            }
            break;
        case '^':
            $result = pow($num1, $num2);
            break;
        case '!':
            $result = factorial($num1);
            break;
        default:
            $result = "Operação inválida!";
    }

    $operationSymbol = ($operation == '!') ? '' : $operation;
    $fullOperation = "$num1 $operationSymbol $num2";
    $resultMessage = ($operation == '!') ? "$num1! = $result" : "$fullOperation = $result";

    addToHistory($resultMessage);
}

if (isset($_POST['clear_history'])) {
    unset($_SESSION['history']);
}

if (isset($_POST['memory'])) {
    if (isset($_SESSION['memory'])) {
        $num1 = $_SESSION['memory']['num1'];
        $num2 = $_SESSION['memory']['num2'];
        $operation = $_SESSION['memory']['operation'];
    } else {
        $num1 = $_POST['num1'];
        $num2 = $_POST['num2'];
        $operation = $_POST['operation'];
        saveToMemory($num1, $num2, $operation);
    }
}

if (isset($_POST['clear_memory'])) {
    clearMemory();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: black;
        }

        .calculator {
            border: 1px solid;
            border-radius: 5px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .calculator h1 {
            margin-top: 0;
            color: black;
        }

        .calculator input[type="text"] {
            width: 80px;
            padding: 5px;
            margin-right: 10px;
        }

        .calculator select {
            padding: 5px;
        }

        .calculator button {
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
        }

        .resultado {
            border: 1px solid;
            border-radius: 5px;
            padding: 5px;
            margin-top: 10px;
            background-color: lightgray;
        }

        .historico {
            border: 1px solid;
            border-radius: 5px;
            padding: 10px;
            margin-top: 20px;
            background-color: white;
        }

        .historico h2 {
            margin-top: 0;
        }

        .historico ul {
            list-style-type: none;
            padding: 0;
        }

        .historico li {
            margin-bottom: 5px;
        }

    </style>
</head>

<body>
    <div class="calculator">
        <h1>Calculadora PHP</h1>
        <form method="post">
            <div><input type="text" name="num1" placeholder="Número 1" value="<?php echo isset($num1) ? $num1 : ''; ?>">
                <select name="operation">
                    <option value="+" <?php echo (isset($operation) && $operation == '+') ? 'selected' : ''; ?>>+</option>
                    <option value="-" <?php echo (isset($operation) && $operation == '-') ? 'selected' : ''; ?>>-</option>
                    <option value="*" <?php echo (isset($operation) && $operation == '*') ? 'selected' : ''; ?>>*</option>
                    <option value="/" <?php echo (isset($operation) && $operation == '/') ? 'selected' : ''; ?>>/</option>
                    <option value="^" <?php echo (isset($operation) && $operation == '^') ? 'selected' : ''; ?>>^</option>
                    <option value="!" <?php echo (isset($operation) && $operation == '!') ? 'selected' : ''; ?>>!</option>
                </select>
                <input type="text" name="num2" placeholder="Número 2" value="<?php echo isset($num2) ? $num2 : ''; ?>">
            </div>
            <button type="submit" name="calculate">Calcular</button>
            <div class="resultado">
                <?php if (isset($resultMessage)) {echo $resultMessage;}?>
            </div>
            <button type="submit" name="memory">M</button>
            <button type="submit" name="clear_memory">Limpar Memória</button>
            <button type="submit" name="clear_history">Limpar Histórico</button>
        </form>
        <h2>Histórico</h2>
        <?php if (isset($_SESSION['history'])): ?>
            <ul>
                <?php foreach ($_SESSION['history'] as $entry): ?>
                    <li><?php echo $entry; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum histórico disponível.</p>
        <?php endif; ?>
    </div>
</body>

</html>






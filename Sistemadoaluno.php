
<?php
$arquivo = "banco_alunos.txt";

// Inicializar variáveis
$alunos = [];
$buscaResultado = [];
$media = null;
$mensagem = "";

// Função para ler alunos do arquivo
if(file_exists($arquivo)){
    $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach($linhas as $linha){
        list($nomeL, $idadeL, $cursoL, $notaL) = explode('|', $linha);
        $alunos[] = [
            'nome' => $nomeL,
            'idade' => $idadeL,
            'curso' => $cursoL,
            'nota' => (float)$notaL
        ];
    }
}

// Cadastrar aluno
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cadastrar"])){
    $nome = $_POST["nome"];
    $idade = $_POST["idade"];
    $curso = $_POST["curso"];
    $nota = $_POST["nota"];

    $linha_de_arquivo = "$nome|$idade|$curso|$nota" . PHP_EOL;
    file_put_contents($arquivo, $linha_de_arquivo, FILE_APPEND);

    $mensagem = "Aluno $nome cadastrado com sucesso!";

    // Atualizar lista de alunos
    $alunos[] = [
        'nome' => $nome,
        'idade' => $idade,
        'curso' => $curso,
        'nota' => (float)$nota
    ];
}

// Buscar aluno
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["buscar"])){
    $nome_busca = strtolower($_POST["nome_busca"]);
    $buscaResultado = array_filter($alunos, function($a) use ($nome_busca){
        return strpos(strtolower($a['nome']), $nome_busca) !== false;
    });
}

// Calcular média da turma
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["calcular_media"])){
    if(count($alunos) > 0){
        $soma = array_sum(array_column($alunos, 'nota'));
        $media = $soma / count($alunos);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Cadastro de Alunos</title>
    <a href="alunosCadastrados.php">Alunos Cadastrados</a>
</head>
<body>

<h1>Sistema de Cadastro de Alunos</h1>

<?php if(!empty($mensagem)) echo "<p><strong>$mensagem</strong></p>"; ?>

<!-- Formulário de Cadastro -->
<h2>Cadastro de Aluno</h2>
<form method="post">
    Nome: <input type="text" name="nome" required><br>
    Idade: <input type="number" name="idade" required><br>
    Curso: <input type="text" name="curso" required><br>
    Nota: <input type="number" step="0.01" name="nota" required><br>
    <button type="submit" name="cadastrar">Cadastrar</button>
</form>


<!-- Busca de Aluno -->
<h2>Buscar Aluno pelo Nome</h2>
<form method="post">
    Nome: <input type="text" name="nome_busca" required>
    <button type="submit" name="buscar">Buscar</button>
</form>

<?php if(!empty($buscaResultado)): ?>
    <h3>Resultado da busca:</h3>
    <?php foreach($buscaResultado as $a): ?>
        Nome: <?= htmlspecialchars($a['nome']) ?> | Idade: <?= htmlspecialchars($a['idade']) ?> | Curso: <?= htmlspecialchars($a['curso']) ?> | Nota: <?= htmlspecialchars($a['nota']) ?><br>
    <?php endforeach; ?>
<?php elseif(isset($_POST["buscar"])): ?>
    <p>Aluno não encontrado.</p>
<?php endif; ?>

<!-- Calcular média da turma -->
<h2>Média da Turma</h2>
<form method="post">
    <button type="submit" name="calcular_media">Calcular Média</button>
</form>

<?php if($media !== null): ?>
    <p>A média da turma é: <?= number_format($media, 2) ?></p>
<?php endif; ?>

</body>
</html>
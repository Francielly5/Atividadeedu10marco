<?php

$arquivo = "banco_alunos.txt";
$alunos = array();

if(file_exists($arquivo)) {

    $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($linhas as $linha) { // foreach correto
        $dados = explode("|", $linha);

        // verifica se tem pelo menos 4 campos
        if(count($dados) >= 4 && trim($dados[0]) != "") {
            $alunos[] = array(
                "nome" => $dados[0],
                "idade" => $dados[1],
                "curso" => $dados[2],
                "nota"  => $dados[3]
            );
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos Cadastrados</title>
</head>
<body>
    <h1>Alunos Cadastrados no Sistema</h1>
    <a href="Sistemadoaluno.php" class="btn-voltar">&larr; Voltar para Cadastro</a>

    <?php if (empty($alunos)): ?>
        <p>Nenhum aluno cadastrado ainda no banco de dados.</p>
    <?php else: ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Idade</th>
                    <th>Curso</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alunos as $aluno): ?>
                <tr>
                    <td><?= htmlspecialchars($aluno['nome']) ?></td>
                    <td><?= htmlspecialchars($aluno['idade']) ?></td>
                    <td><?= htmlspecialchars($aluno['curso']) ?></td>
                    <td><?= htmlspecialchars($aluno['nota']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
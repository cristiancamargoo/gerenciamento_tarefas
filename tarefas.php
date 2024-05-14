<?php
$con = new PDO ("mysql:host=localhost;dbname=tarefas","root","root");
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Processamento do formulário de adição de tarefa
if (isset($_POST['tarefa'])) {
    $tarefa = filter_input(INPUT_POST, 'tarefa', FILTER_SANITIZE_STRING);
    $query = "INSERT INTO tarefas (descricao, concluida) VALUES (:descricao, 0)";
    $stm = $con->prepare($query);
    $stm->bindParam(':descricao', $tarefa); // Corrigido para :descricao
    $stm->execute();
    header('Location: http://localhost/tarefas/tarefas.php');
    exit;
}

// Processamento do pedido de exclusão
if (isset($_GET['excluir'])) {
    $id = filter_input(INPUT_GET, 'excluir', FILTER_SANITIZE_NUMBER_INT);
    $query = "DELETE FROM tarefas WHERE id=:id";
    $stm = $con->prepare($query);
    $stm->bindParam(':id', $id); // Corrigido para :id
    $stm->execute();
    header('Location: http://localhost/tarefas/tarefas.php');
    exit;
}

// Processamento do pedido de conclusão
if (isset($_GET['concluir'])) {
    $id = filter_input(INPUT_GET, 'concluir', FILTER_SANITIZE_NUMBER_INT);
    $query = "UPDATE tarefas SET concluida = 1 WHERE id=:id";
    $stm = $con->prepare($query);
    $stm->bindParam(':id', $id); // Corrigido para :id
    $stm->execute();
    header('Location: http://localhost/tarefas/tarefas.php');
    exit;
}

// Processamento do formulário de edição
if (isset($_POST['editar'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    $query = "UPDATE tarefas SET descricao = :descricao WHERE id=:id";
    $stm = $con->prepare($query);
    $stm->bindParam(':descricao', $descricao);
    $stm->bindParam(':id', $id);
    $stm->execute();
    header('Location: http://localhost/tarefas/tarefas.php');
    exit;
}

// Consulta todas as tarefas
$query = "SELECT id, descricao, concluida FROM tarefas";
$lista = $con->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=], initial-scale=1.0">
    <title>Lista de Tarefas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Tarefas</h1>
        <form action="" method="post">
            <label for="tarefa">Nova Tarefa:</label>
            <input type="text" name="tarefa" id="tarefa"/>
            <input type="submit" value="Incluir"/>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>Tarefa</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Conexão com o banco de dados
                $con = new PDO("mysql:host=localhost;dbname=tarefas", "root", "root");
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Processamento do formulário de adição de tarefa
                if (isset($_POST['tarefa'])) {
                    $tarefa = filter_input(INPUT_POST, 'tarefa', FILTER_SANITIZE_STRING);
                    $query = "INSERT INTO tarefas (descricao, concluida) VALUES (:descricao, 0)";
                    $stm = $con->prepare($query);
                    $stm->bindParam(':descricao', $tarefa);
                    $stm->execute();
                }
                
                // Processamento do pedido de exclusão
                if (isset($_GET['excluir'])) {
                    $id = filter_input(INPUT_GET, 'excluir', FILTER_SANITIZE_NUMBER_INT);
                    $query = "DELETE FROM tarefas WHERE id=:id";
                    $stm = $con->prepare($query);
                    $stm->bindParam(':id', $id);
                    $stm->execute();
                }
                
                // Processamento do pedido de conclusão
                if (isset($_GET['concluir'])) {
                    $id = filter_input(INPUT_GET, 'concluir', FILTER_SANITIZE_NUMBER_INT);
                    $query = "UPDATE tarefas SET concluida = 1 WHERE id=:id";
                    $stm = $con->prepare($query);
                    $stm->bindParam(':id', $id);
                    $stm->execute();
                }
                
                // Processamento do formulário de edição
                if (isset($_POST['editar'])) {
                    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
                    $query = "UPDATE tarefas SET descricao = :descricao WHERE id=:id";
                    $stm = $con->prepare($query);
                    $stm->bindParam(':descricao', $descricao);
                    $stm->bindParam(':id', $id);
                    $stm->execute();
                }
                
                // Consulta todas as tarefas
                $query = "SELECT id, descricao, concluida FROM tarefas";
                $lista = $con->query($query)->fetchAll();
                
                // Exibição das tarefas na tabela
                foreach($lista as $item):
                ?>
                <tr <?= $item['concluida']?'class="concluida"':'' ?>>
                    <td><?= $item['descricao'] ?></td>
                    <td class="acoes">
                        <?php if(!$item['concluida']): ?>
                            <a class="concluir" href="?concluir=<?= $item['id'] ?>">Concluir</a>
                        <?php endif; ?>
                        <a class="editar" href="?editar=<?= $item['id'] ?>">Editar</a>
                        <a class="excluir" href="?excluir=<?= $item['id'] ?>">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if(isset($_GET['editar'])): ?>
            <?php
            $idEditar = filter_input(INPUT_GET, 'editar', FILTER_SANITIZE_NUMBER_INT);
            $query = "SELECT descricao FROM tarefas WHERE id=:id";
            $stm = $con->prepare($query);
            $stm->bindParam(':id', $idEditar);
            $stm->execute();
            $tarefaEditar = $stm->fetch(PDO::FETCH_ASSOC);
            ?>
            <form action="" method="post">
                <input type="hidden" name="id" value="<?= $idEditar ?>"/>
                Editar Tarefa: <input type="text" name="descricao" value="<?= $tarefaEditar['descricao'] ?>"/>
                <input type="submit" name="editar" value="Salvar"/>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Certifique-se de que a conexão esteja incluída se não estiver no arquivo pai
include_once('../config/conexao.php');

if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit;
}

// Obtém e filtra o parâmetro 'id'
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: home.php");
    exit;
}

// Prepara e executa a consulta para buscar os dados atuais do contato
$select = "SELECT * FROM tb_contatos WHERE id_contatos = :id";

try {
    $resultado = $conect->prepare($select);
    $resultado->bindParam(':id', $id, PDO::PARAM_INT);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        $show  = $resultado->fetch(PDO::FETCH_OBJ);
        $idCont = $show->id_contatos;
        $nome   = $show->nome_contatos;
        $fone   = $show->fone_contatos;
        $email  = $show->email_contatos;
        $foto   = $show->foto_contatos;
    } else {
        // Se o contato não for encontrado, redireciona ou encerra
        header("Location: home.php");
        exit;
    }
} catch (PDOException $e) {
    echo "<strong>ERRO DE SELECT NO PDO: </strong>" . $e->getMessage();
    exit;
}

// Processamento da Atualização do Formulário
$mensagem = '';
if (isset($_POST['upContato'])) {
    $nome  = $_POST['nome'];
    $fone  = $_POST['telefone'];
    $email = $_POST['email'];
    $novoNome = $foto; // Mantém a foto atual por padrão

    // Verifica se foi enviado um novo arquivo de foto
    if (isset($_FILES['foto']) && !empty($_FILES['foto']['name'])) {
        $formatP  = array("png", "jpg", "jpeg", "gif");
        // Converter a extensão para minúsculas para evitar rejeição de .JPG
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        if (in_array($extensao, $formatP)) {
            $pasta      = "../img/cont/";
            $temporario = $_FILES['foto']['tmp_name'];
            $tempNome   = uniqid() . ".{$extensao}";

            if (move_uploaded_file($temporario, $pasta . $tempNome)) {
                // Deleta a foto antiga apenas se não for imagem padrão e o arquivo existir
                $fotosPadrao = array('avatar_padrao.png', 'avatar-padrao.png');
                if ($foto && !in_array($foto, $fotosPadrao) && file_exists($pasta . $foto)) {
                    unlink($pasta . $foto);
                }
                
                $novoNome = $tempNome;
                $foto     = $novoNome; // Atualiza a variável para exibir a foto nova no card imediatamente
            } else {
                $mensagem = '<div class="alert alert-warning">Erro ao fazer upload da imagem.</div>';
            }
        } else {
            $mensagem = '<div class="alert alert-warning">Formato de imagem inválido! Use PNG, JPG, JPEG ou GIF.</div>';
        }
    }

    // Executa o UPDATE no Banco de Dados
    $update = "UPDATE tb_contatos SET nome_contatos = :nome, fone_contatos = :fone, email_contatos = :email, foto_contatos = :foto WHERE id_contatos = :id";
    
    try {
        $result = $conect->prepare($update);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':nome', $nome, PDO::PARAM_STR);
        $result->bindParam(':fone', $fone, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':foto', $novoNome, PDO::PARAM_STR);

        if ($result->execute()) {
            $mensagem = '<div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> OK!</h5>
                            Dados atualizados com sucesso.
                         </div>';
            header("Refresh: 3; url=home.php");
        } else {
            $mensagem = '<div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-ban"></i> Erro!</h5>
                            Não foi possível atualizar os dados.
                         </div>';
        }
    } catch (PDOException $e) {
        $mensagem = "<strong>ERRO DE PDO: </strong>" . $e->getMessage();
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1>Editar Contato</h1>
        </div>
      </div>
      <?php 
        // Exibe mensagens de feedback caso existam
        if (!empty($mensagem)) {
            echo $mensagem;
        } 
      ?>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- Coluna Esquerda: Formulário -->
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Formulário de Edição</h3>
            </div>
            <!-- form start -->
            <form role="form" action="" method="post" enctype="multipart/form-data">
              <div class="card-body">
                <div class="form-group">
                  <label for="nome">Nome</label>
                  <input type="text" class="form-control" name="nome" id="nome" required value="<?php echo htmlspecialchars($nome); ?>">
                </div>
                <div class="form-group">
                  <label for="telefone">Telefone</label>
                  <input type="text" class="form-control" name="telefone" id="telefone" required value="<?php echo htmlspecialchars($fone); ?>">
                </div>
                <div class="form-group">
                  <label for="email">Endereço de E-mail</label>
                  <input type="email" class="form-control" name="email" id="email" required value="<?php echo htmlspecialchars($email); ?>">
                </div>
                
                <div class="form-group">
                  <label for="foto">Foto do contato</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="foto" id="foto">
                      <label class="custom-file-label" for="foto">Escolher nova imagem</label>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" name="upContato" class="btn btn-primary">Finalizar edição do contato</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Coluna Direita: Prévia do Contato -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Dados do Contato</h3>
            </div>
            <div class="card-body d-flex flex-column align-items-center p-4" style="text-align: center;">
              <!-- Foto redonda e centralizada -->
              <img src="../img/cont/<?php echo htmlspecialchars($foto); ?>" alt="<?php echo htmlspecialchars($nome); ?>" class="rounded-circle shadow" style="width: 200px; height: 200px; object-fit: cover; margin-bottom: 20px; border: 3px solid #007bff; padding: 3px;">
              
              <!-- Dados do contato -->
              <h2><?php echo htmlspecialchars($nome); ?></h2>
              <strong><?php echo htmlspecialchars($fone); ?></strong>
              <p><?php echo htmlspecialchars($email); ?></p>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
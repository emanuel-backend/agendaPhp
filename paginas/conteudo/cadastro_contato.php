<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Cadastro de Contatos</h1>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        
        <!-- Coluna Esquerda: Formulário de Cadastro -->
        <div class="col-md-4">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Cadastrar contato</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" action="" method="post" enctype="multipart/form-data">
              <div class="card-body">
                <div class="form-group">
                  <label for="nome">Nome</label>
                  <input type="text" class="form-control" name="nome" id="nome" required placeholder="Digite o nome de contato">
                </div>
                <div class="form-group">
                  <label for="telefone">Telefone</label>
                  <input type="text" class="form-control" name="telefone" id="telefone" required placeholder="(00) 00000-0000">
                </div>
                <div class="form-group">
                  <label for="email">Endereço de E-mail</label>
                  <input type="email" class="form-control" name="email" id="email" required placeholder="Digite um e-mail">
                </div>
                
                <div class="form-group">
                  <label for="foto">Foto do contato</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="foto" id="foto">
                      <label class="custom-file-label" for="foto">Arquivo de imagem</label>
                    </div>
                  </div>
                </div>

                <input type="hidden" name="id_user" id="id_user" value="<?php echo htmlspecialchars($id_user); ?>">

                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                  <label class="form-check-label" for="exampleCheck1">Autorizo o cadastro do meu contato</label>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" name="botao" class="btn btn-primary">Cadastrar Contato</button>
              </div>
            </form>

            <?php
            include_once('../config/conexao.php');

            // Verifica se o formulário foi submetido
            if (isset($_POST['botao'])) {
                // Recupera os valores do formulário
                $nome       = $_POST['nome'];
                $telefone   = $_POST['telefone'];
                $email      = $_POST['email'];
                $id_usuario = $_POST['id_user'];

                // Formatos de imagem permitidos
                $formatP = array("png", "jpg", "jpeg", "gif");

                // Verifica se a imagem foi enviada e se é válida
                if (isset($_FILES['foto']) && !empty($_FILES['foto']['name'])) {
                    $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

                    if (in_array($extensao, $formatP)) {
                        $pasta = "../img/cont/";
                        $temporario = $_FILES['foto']['tmp_name'];
                        $novoNome = uniqid() . ".$extensao";

                        if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                            $foto = $novoNome;
                        } else {
                            echo '<div class="p-3"><div class="alert alert-warning">Erro ao fazer upload do arquivo! Avatar padrão utilizado.</div></div>';
                            $foto = 'avatar_padrao.png';
                        }
                    } else {
                        echo '<div class="p-3"><div class="alert alert-warning">Formato inválido! Avatar padrão utilizado.</div></div>';
                        $foto = 'avatar_padrao.png';
                    }
                } else {
                    $foto = 'avatar_padrao.png';
                }

                // Prepara a consulta SQL para inserir os dados no banco
                $cadastro = "INSERT INTO tb_contatos (nome_contatos, fone_contatos, email_contatos, foto_contatos, id_user) VALUES (:nome, :telefone, :email, :foto, :id_user)";

                try {
                    $result = $conect->prepare($cadastro);
                    $result->bindParam(':nome', $nome, PDO::PARAM_STR);
                    $result->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                    $result->bindParam(':email', $email, PDO::PARAM_STR);
                    $result->bindParam(':foto', $foto, PDO::PARAM_STR);
                    $result->bindParam(':id_user', $id_usuario, PDO::PARAM_INT);

                    $result->execute();

                    if ($result->rowCount() > 0) {
                        echo '<div class="p-3"><div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-check"></i> OK!</h5>
                                Dados inseridos com sucesso !!!
                              </div></div>';
                        header("Refresh: 2; url=home.php");
                    } else {
                        echo '<div class="p-3"><div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-ban"></i> Erro!</h5>
                                Dados não inseridos !!!
                              </div></div>';
                        header("Refresh: 2; url=home.php");
                    }
                } catch (PDOException $e) {
                    echo '<div class="p-3"><strong>ERRO DE PDO= </strong>' . $e->getMessage() . '</div>';
                }
            }
            ?>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col-md-4 -->

        <!-- Coluna Direita: Contatos Recentes -->
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Contatos Recentes</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th style="width: 40px">Ações</th>
                  </tr>
                </thead>
                <table class="table table-striped">
  <thead>
    <tr>
      <th style="width: 10px">#</th>
      <th>Foto</th>
      <th>Nome</th>
      <th>Telefone</th>
      <th>E-mail</th>
      <th style="width: 40px">Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php
      // Seleciona os contactos em ordem decrescente
      $select = "SELECT * FROM tb_contatos WHERE id_user = :id_user ORDER BY id_contatos DESC LIMIT 6";

      try {
          $result = $conect->prepare($select);
          $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
          $cont = 1;
          $result->execute();

          if ($result->rowCount() > 0) {
              while ($show = $result->fetch(PDO::FETCH_OBJ)) {
                  // Verifica o caminho da foto
                  $avataresPadrao = array('avatar-padrao.png', 'avatar_padrao.png');
                  
                  if (in_array($show->foto_contatos, $avataresPadrao) || empty($show->foto_contatos)) {
                      $caminhoFoto = '../img/avatar_p/avatar_padrao.png';
                  } else if (file_exists('../img/cont/' . $show->foto_contatos)) {
                      $caminhoFoto = '../img/cont/' . $show->foto_contatos;
                  } else {
                      $caminhoFoto = '../img/avatar_p/avatar_padrao.png';
                  }
      ?>
              <tr>
                  <td><?php echo $cont++; ?></td>
                  
                  <!-- 1. Célula da Foto -->
                  <td>
                    <img src="<?php echo $caminhoFoto; ?>" alt="<?php echo htmlspecialchars($show->nome_contatos); ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                  </td>
                  
                  <!-- 2. Célula do Nome -->
                  <td><?php echo htmlspecialchars($show->nome_contatos); ?></td>
                  
                  <!-- 3. Célula do Telefone -->
                  <td><?php echo htmlspecialchars($show->fone_contatos); ?></td>
                  
                  <!-- 4. Célula do E-mail -->
                  <td><?php echo htmlspecialchars($show->email_contatos); ?></td>
                  
                  <!-- 5. Célula das Ações -->
                  <td>
                    <div class="btn-group">
                        <a href="home.php?acao=editar&id=<?php echo $show->id_contatos; ?>" class="btn btn-success" title="Editar Contato"><i class="fas fa-user-edit"></i></a>
                        <a href="conteudo/del-contato.php?idDel=<?php echo $show->id_contatos; ?>" onclick="return confirm('Deseja remover o contato')" class="btn btn-danger" title="Remover Contato"><i class="fas fa-user-times"></i></a>
                    </div>
                  </td>
              </tr>
      <?php
              }
          }
      } catch (PDOException $e) {
          echo '<tr><td colspan="6" class="p-3"><strong>ERRO DE PDO= </strong>' . $e->getMessage() . '</td></tr>';
      }
      ?>                
  </tbody>
</table>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col-md-8 -->

      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
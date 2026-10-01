<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Lista de contatos</h1>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Lista de contatos</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example" class="display nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Seleciona os contatos do utilizador atual em ordem decrescente
                  $select = "SELECT * FROM tb_contatos WHERE id_user = :id_user ORDER BY id_contatos DESC";

                  try {
                      $result = $conect->prepare($select);
                      $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                      $result->execute();

                      $cont = 1;
                      if ($result->rowCount() > 0) {
                          while ($show = $result->fetch(PDO::FETCH_OBJ)) {
                              // Nomes comuns de imagens padrão
                              $avataresPadrao = array('avatar-padrao.png', 'avatar_padrao.png', 'avatar-padrao.jpg');

                              // Lógica de verificação do caminho da foto
                              if (in_array($show->foto_contatos, $avataresPadrao) || empty($show->foto_contatos)) {
                                  $caminhoFoto = '../img/avatar_p/avatar_padrao.png';
                              } else if (file_exists('../img/cont/' . $show->foto_contatos)) {
                                  $caminhoFoto = '../img/cont/' . $show->foto_contatos;
                              } else {
                                  // Se o ficheiro não for encontrado no servidor, exibe o avatar padrão
                                  $caminhoFoto = '../img/avatar_p/avatar_padrao.png';
                              }
                  ?>
                              <tr>
                                <td><?php echo $cont++; ?></td>
                                <td>
                                  <img src="<?php echo $caminhoFoto; ?>" alt="<?php echo htmlspecialchars($show->nome_contatos); ?>" style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%;">
                                </td>
                                <td><?php echo htmlspecialchars($show->nome_contatos); ?></td>
                                <td><?php echo htmlspecialchars($show->fone_contatos); ?></td>
                                <td><?php echo htmlspecialchars($show->email_contatos); ?></td>
                                <td>
                                  <div class="btn-group">
                                    <a href="home.php?acao=editar&id=<?php echo $show->id_contatos; ?>" class="btn btn-success" title="Editar Contato"><i class="fas fa-user-edit"></i></a>
                                    <a href="conteudo/del-rel-contato.php?idDelete=<?php echo $show->id_contatos; ?>" onclick="return confirm('Deseja remover o contato?')" class="btn btn-danger" title="Remover Contato"><i class="fas fa-user-times"></i></a>
                                  </div>
                                </td>
                              </tr>
                  <?php
                          }
                      }
                  } catch (PDOException $e) {
                      echo '<tr><td colspan="6"><strong>ERRO DE PDO: </strong>' . $e->getMessage() . '</td></tr>';
                  }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
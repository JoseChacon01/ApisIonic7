
<?php
//Esse arquivo "excluir.php" deverá fazer a "ponte/comunicação" do que foi excluido do meu Banco de Dados. 
    require_once('../conexao.php'); //  'require_once': incluir um arquivo PHP em outro -- Conecta inserir.php com conexao.php
    $postjson = json_decode(file_get_contents('php://input'), true); 
   
    $id = @$postjson['id'];        

	$res = $pdo->query("DELETE FROM usuarios where id = '$id'"); //Delete o usuario onde o id seja igual a variavel que carrega o id.


    $result = json_encode(array('mensagem'=>'Excluido com Sucesso', 'ok'=>true)); 
    echo $result;                                                                  //Poderia ser difina uma mensagem de erro, por exemplo: Se fossemos excluir uma categoria que tem produtos atrelados a ela, ira aparecer "não possivel excluir essa categoria, pois existem produtos que pertencem a ela".
?>
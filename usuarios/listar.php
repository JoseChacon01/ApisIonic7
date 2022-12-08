<?php
   
    require_once('../conexao.php'); //  'require_once': incluir um arquivo PHP em outro -- Conecta inserir.php com conexao.php
    $postjson = json_decode(file_get_contents('php://input'), true); // Essa função irá receber tudo que vier das caixas de textos, ou seja "inputs" (nome, cpf, email...). 

    $busca = '%' .$postjson['nome']. '%'; //Na busca ele vai buscar um valor aproximado do que foi inserido na variavel 'nome' definida em usuarios.page, essa variavel recebe os dados da busca (nome ou cpf). -- SCRIPTS PRONTOS 10
    $query = $pdo->query("SELECT * from usuarios where nome LIKE '$busca' or cpf LIKE '$busca' order by id desc limit $postjson[start], $postjson[limit]"); //Vai exibir todos os usuarios que possuem o nome ou cpf semelhante ao digitado na variavel '$busta' -- Colocamos um limite, que vai exibir  por pagina
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg > 0){ 

        for($i=0; $i < $total_reg; $i++){
            foreach ($res[$i] as $key => $value){	}

            $dados[] = array(
                'id' => $res[$i]['id'],
                'nome' => $res[$i]['nome'],
                'cpf' => $res[$i]['cpf'],
                'email' => $res[$i]['email'],
                'senha' => $res[$i]['senha'],
                'nivel' => $res[$i]['nivel'],

            );

        }
        
        $result = json_encode(array('itens_n'=>$dados)); 
        echo $result;

    }else{
        $result = json_encode(array('itens_n'=>'0')); 
        echo $result;
    }

?>   
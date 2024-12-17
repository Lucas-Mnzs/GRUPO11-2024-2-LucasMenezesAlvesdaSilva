<?php

namespace Name\Models;

use Name\Controllers\taxaController;
use Name\Models\Config\Conexao;
use PDO;

class HomeModels
{
    private $con;

    public function __construct()
    {
        $this->con = Conexao::getConexao();
    }

    public function loginUser($data)
    {
        $cmdUser = $this->con->prepare('SELECT * FROM usuarios WHERE ativo = "sim" AND usuario = :usuario LIMIT 1');
        $cmdUser->bindParam(':usuario', $data['user']);
        $cmdUser->execute();
        $usuario = $cmdUser->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            return json_encode(['status' => 'errorUser', 'message' => 'Usuário não encontrado.']);
        }

        if (password_verify($data['pass'], $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario['usuario'];
            $_SESSION['id_usuario'] = $usuario['idUsuarios'];
            return json_encode(['status' => 'success']);
        } else {
            return json_encode(['status' => 'errorPass', 'message' => 'Senha incorreta.']);
        }
    }

    public function segundaAut($data)
    {
        $cmd = $this->con->prepare('SELECT * FROM usuarios WHERE ativo = "sim" AND usuario = :usuario LIMIT 1');
        $cmd->execute([
            ':usuario' => $_SESSION['usuario']
        ]);
        $usuario = $cmd->fetch(PDO::FETCH_ASSOC);

        if ($data['resposta'] === $usuario['cep']) {
            $pergunta = "Qual o CEP do seu endereço?";
        } elseif ($data['resposta'] === $usuario['nomeMae']) {
            $pergunta = "Qual o nome da sua mãe?";
        } else {
            $pergunta = "Qual a data do seu nascimento?";
        }

        if (
            $data['resposta'] === $usuario['cep'] ||
            $data['resposta'] === $usuario['nomeMae'] ||
            $data['resposta'] === $usuario['dataNascimento']
        ) {
            if (!empty($data['remember'])) {
                $token = bin2hex(random_bytes(32));
                $expiresAt = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 dias

                $stmt = $this->con->prepare("
                    INSERT INTO remember_tokens (user_id, token, expires_at) 
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$_SESSION['id_usuario'], $token, $expiresAt]);

                setcookie('remember_token', $token, time() + (86400 * 30), "/");
            }
            $cmd_logs = $this->con->prepare("
                INSERT INTO logs (id_usuario, acao)
                VALUES (?, ?)
            ");
            $cmd_logs->execute([$_SESSION['id_usuario'], "A pergunta foi: $pergunta"]);

            if ($usuario['tipo_usuario'] === "master" || $usuario['tipo_usuario'] === "funcionario") {
                $_SESSION['sobrenome']       = $usuario['sobrenome'];
                $_SESSION['contato']         = $usuario['cell'];
                $_SESSION['email']           = $usuario['email'];
                $_SESSION['estado']          = $usuario['estado'];
                $_SESSION['cidade']          = $usuario['cidade'];
                $_SESSION['bairro']          = $usuario['bairro'];
                $_SESSION['rua']             = $usuario['rua'];
                $_SESSION['numero']          = $usuario['numero'];
                $_SESSION['complemento']     = $usuario['complemento'];
                $_SESSION['referencia']      = $usuario['referencia'];
                $_SESSION['cep']             = $usuario['cep'];
                $_SESSION['tipo_usuario']    = $usuario['tipo_usuario'];
                return json_encode(['status' => 'successHost']);
            }
            $_SESSION['nome']         = $usuario['pnome'];
            $_SESSION['sobrenome']    = $usuario['sobrenome'];
            $_SESSION['contato']      = $usuario['cell'];
            $_SESSION['email']        = $usuario['email'];
            $_SESSION['estado']       = $usuario['estado'];
            $_SESSION['cidade']       = $usuario['cidade'];
            $_SESSION['bairro']       = $usuario['bairro'];
            $_SESSION['rua']          = $usuario['rua'];
            $_SESSION['numero']       = $usuario['numero'];
            $_SESSION['complemento']  = $usuario['complemento'];
            $_SESSION['referencia']   = $usuario['referencia'];
            $_SESSION['cep']          = $usuario['cep'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            $taxaController = new taxaController;
            $taxa = $taxaController->getTaxa();

            if ($taxa) {
                return json_encode(['status' => 'success']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'Resposta de recuperação incorreta.']);
            }
        } else {
            return json_encode(['status' => 'error', 'message' => 'Resposta de recuperação incorreta.']);
        }
    }

    public function recuperarUser($data)
    {
        $cmd = $this->con->prepare('SELECT * FROM usuarios WHERE ativo = "sim" AND usuario = :usuario LIMIT 1');
        $cmd->bindParam(':usuario', $data['user']);
        $cmd->execute();
        $usuario = $cmd->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário foi encontrado
        if (!$usuario) {
            return json_encode(['status' => 'errorUser', 'message' => 'Usuário não encontrado.']);
        }

        // Verifica se o email é correto
        if ($usuario['email'] !== $data['email']) {
            return json_encode(['status' => 'errorEmail', 'message' => 'E-mail incorreto.']);
        }

        // Verifica se a resposta de recuperação está correta
        if (
            $data['resposta'] === $usuario['cep'] ||
            $data['resposta'] === $usuario['nomeMae'] ||
            $data['resposta'] === $usuario['dataNascimento']
        ) {
            // Armazena informações na sessão apenas se a recuperação for bem-sucedida
            $_SESSION['id_usuario'] = $usuario['idUsuarios'];
            $_SESSION['usuario'] = $data['user'];

            // Registra o log de alteração
            $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES (?, ?)
        ");
            $cmd_logs->execute([$_SESSION['id_usuario'], "Senha Alterada!"]);

            return json_encode(['status' => 'success']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Resposta de recuperação incorreta.']);
        }
    }


    public function atualizarSenha($data)
    {
        if (!empty($data['senha'])) {
            $cmd = $this->con->prepare('UPDATE usuarios SET senha = :senha WHERE usuario = :usuario ');
            $cmd->execute([
                ':senha'    => $data['senha'],
                ':usuario'  => $_SESSION['usuario']
            ]);

            return json_encode(['status' => 'success']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Preencha todos os campos']);
        }
    }

    public function cadastrarUser($data)
    {
        if (
            !empty($data['nome']) &&
            !empty($data['sNome']) &&
            !empty($data['nomeMae']) &&
            !empty($data['dataNascimento']) &&
            !empty($data['cpf']) &&
            !empty($data['cell']) &&
            !empty($data['email']) &&
            !empty($data['estado']) &&
            !empty($data['cidade']) &&
            !empty($data['bairro']) &&
            !empty($data['rua']) &&
            !empty($data['numero']) &&
            !empty($data['referencia']) &&
            !empty($data['cep']) &&
            !empty($data['user']) &&
            !empty($data['senha'])
        ) {
            // Verificar duplicidade no banco de dados
            $cmdCheck = $this->con->prepare('
                SELECT * 
                FROM usuarios 
                WHERE ativo = "sim" AND (usuario = :usuario OR email = :email OR cpf = :cpf OR cell = :cell)
            ');
            $cmdCheck->execute([
                ':usuario' => $data['user'],
                ':email' => $data['email'],
                ':cpf' => $data['cpf'],
                ':cell' => $data['cell'],
            ]);

            $existingUser = $cmdCheck->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                // Retornar mensagens específicas dependendo do conflito
                if ($existingUser['usuario'] === $data['user']) {
                    return json_encode(['status' => 'errorUser', 'message' => 'Usuário já existe.']);
                }
                if ($existingUser['email'] === $data['email']) {
                    return json_encode(['status' => 'errorEmail', 'message' => 'E-mail já cadastrado.']);
                }
                if ($existingUser['cpf'] === $data['cpf']) {
                    return json_encode(['status' => 'errorCpf', 'message' => 'CPF já cadastrado.']);
                }
                if ($existingUser['cell'] === $data['cell']) {
                    return json_encode(['status' => 'errorCell', 'message' => 'Celular já cadastrado.']);
                }
            }

            // Inserir novo usuário
            $cmd = $this->con->prepare('
                INSERT INTO usuarios (pnome, sobrenome, nomeMae, dataNascimento, cpf, cell, email, estado, cidade, bairro, rua, numero, complemento, referencia, cep, usuario, senha, tipo_usuario) 
                VALUES (:pnome, :sobrenome, :nomeMae, :dataNascimento, :cpf, :cell, :email, :estado, :cidade, :bairro, :rua, :numero, :complemento, :referencia, :cep, :usuario, :senha, "cliente")
            ');

            $cmd->execute([
                ':pnome' => $data['nome'],
                ':sobrenome' => $data['sNome'],
                ':nomeMae' => $data['nomeMae'],
                ':dataNascimento' => $data['dataNascimento'],
                ':cpf' => $data['cpf'],
                ':cell' => $data['cell'],
                ':email' => $data['email'],
                ':estado' => $data['estado'],
                ':cidade' => $data['cidade'],
                ':bairro' => $data['bairro'],
                ':rua' => $data['rua'],
                ':numero' => $data['numero'],
                ':complemento' => $data['complemento'],
                ':referencia' => $data['referencia'],
                ':cep' => $data['cep'],
                ':usuario' => $data['user'],
                ':senha' => $data['senha'],
            ]);

            // Retorno de sucesso
            return json_encode(['status' => 'success', 'message' => 'Usuário cadastrado com sucesso.']);
        } else {
            // Caso falte algum campo obrigatório
            return json_encode(['status' => 'error', 'message' => 'Por favor, preencha todos os campos obrigatórios.']);
        }
    }
}
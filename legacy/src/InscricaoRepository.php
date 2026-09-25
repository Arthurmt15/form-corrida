<?php
// Contexto: camada de dados — SQL concentrado aqui (pessoas 1:N inscricoes).
// Entradas (salvar/api/listar) chamam estes métodos; nunca escrevem SQL inline.

class InscricaoRepository {
  // Salva pessoa (reaproveita por CPF/CNPJ) + inscrição em transação. Retorna id da inscrição.
  // Lança RuntimeException('DUPLICADO') se pessoa já inscrita na mesma distância.
  public static function salvar(PDO $pdo, array $pessoa, array $insc): int {
    try {
      $pdo->beginTransaction();
      $stmt = $pdo->prepare('SELECT id FROM pessoas WHERE cpf_cnpj = :doc');
      $stmt->execute([':doc' => $pessoa['cpf_cnpj']]);
      $row = $stmt->fetch();
      if ($row) {
        $pessoaId = (int) $row['id'];
      } else {
        $cols = array_keys($pessoa);
        $stmt = $pdo->prepare('INSERT INTO pessoas (' . implode(',', $cols) . ') VALUES (:' . implode(',:', $cols) . ')');
        $stmt->execute(array_combine(array_map(fn($c) => ":$c", $cols), array_values($pessoa)));
        $pessoaId = (int) $pdo->lastInsertId();
      }
      $insc['pessoa_id'] = $pessoaId;
      $cols = array_keys($insc);
      $stmt = $pdo->prepare('INSERT INTO inscricoes (' . implode(',', $cols) . ') VALUES (:' . implode(',:', $cols) . ')');
      $stmt->execute(array_combine(array_map(fn($c) => ":$c", $cols), array_values($insc)));
      $id = (int) $pdo->lastInsertId();
      $pdo->commit();
      return $id;
    } catch (PDOException $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      if ($e->getCode() === '23000') throw new RuntimeException('DUPLICADO');
      throw $e;
    }
  }

  // Contexto: WHERE de busca (nome/e-mail/cidade) compartilhado por contar() e buscar().
  private static function filtro(string $q, array &$params): string {
    if (trim($q) === '') return '';
    $params[':q'] = '%' . trim($q) . '%';
    return 'WHERE p.nome LIKE :q OR p.email LIKE :q OR p.cidade LIKE :q';
  }

  // Contexto: total de inscrições (com o mesmo filtro da busca) para a paginação.
  public static function contar(PDO $pdo, string $q): int {
    $params = [];
    $stmt = $pdo->prepare('SELECT COUNT(*) AS t FROM inscricoes i JOIN pessoas p ON p.id = i.pessoa_id ' . self::filtro($q, $params));
    $stmt->execute($params);
    return (int) $stmt->fetch()['t'];
  }

  // Contexto: página de inscrições com JOIN pessoa (mais recentes primeiro).
  public static function buscar(PDO $pdo, string $q, int $limite, int $offset): array {
    $params = [];
    $stmt = $pdo->prepare(
      'SELECT i.id, p.nome, p.nome_social, p.tipo_pessoa, p.cpf_cnpj, p.email, p.celular,
              p.tem_whatsapp, p.cidade, p.uf, i.distancia, i.categoria, i.equipe,
              i.origem, i.status_cadastro, i.criado_em
       FROM inscricoes i JOIN pessoas p ON p.id = i.pessoa_id '
      . self::filtro($q, $params) . " ORDER BY i.criado_em DESC LIMIT $limite OFFSET $offset"
    );
    $stmt->execute($params);
    return $stmt->fetchAll();
  }
}

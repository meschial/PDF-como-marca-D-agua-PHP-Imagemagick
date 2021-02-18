<!doctype html>
<html lang="pt-BR">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <title>Marca D'Agua</title>
  </head>
  <body>
    <div class="container mt-3">
        <form method="post" action="convert/convert_pdf.php">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" required name="nome" placeholder="Allan Godoy">
        </div>
        <div class="mb-3">
            <label class="form-label">CPF</label>
            <input type="text" class="form-control" required id="cpf" name="cpf" placeholder="999.999.999-99">
        </div>
        <button type="submit" class="btn btn-primary">Gerar PDF</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

    <script type="text/javascript">
    $("#cpf").mask("999.999.999-99");
    </script>

  </body>
</html>
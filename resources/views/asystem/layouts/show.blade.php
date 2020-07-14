<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $layout->title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        td, th {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        table {
            max-width: 800px;
            text-align: left;
        }
        th {
            background-color: #ddd
        }
    </style>
</head>
<body>
    <h3>{{ $layout->title }}</h3>
    <table cellspacing="0">
    <thead>
        <tr>
            <th>Наименование</th>
            <th>Количество</th>
            <th>Цена</th>
            <th>Сумма</th>
        </tr>
    </thead>
    <tbody>
    @foreach($layout->layoutMaterial as $lm)
    <tr>
        <td>
            @if($lm->isPattern())
                {{ $lm->selectedMaterial->title }}
            @else
                {{ $lm->position->title }}
            @endif
        </td>
        <td>{{ $lm->count }}</td>
        <td>{{ $lm->getPositionPrice() }}</td>
        <td>{{ $lm->getPositionSum() }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th colspan="4">Итого {{ $layout->getTotalAmount() }}</th>
      </tr>
    </tfoot>
    
</table>
</body>
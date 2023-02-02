<x-layout title="Séries">
    <div>
    <a href="{{ route('series.create') }}" class="btn btn-dark mb-2">Adicionar</a>
    </div>
    <div class="d-flex float-right">   
        <form action="{{ route('series.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        Ou faça upload em formato de planilha:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <br>
        <input type="submit" value="Enviar Planilha" name="submit" class="btn btn-dark mb-2">
        </form>
        <br>
    </div>

@isset($mensagemSucesso)
<div class="alert alert-success">    
    {{ $mensagemSucesso }}
</div>
@endisset

    <ul class="list-group">
        @foreach ($series as $serie)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('seasons.index', $serie->id) }}">
                    {{ $serie->nome }}
                </a>

                <span class="d-flex">
                    <a href="{{ route('series.edit', $serie->id) }}" class="btn btn-primary btn-sm">
                        Editar
                    </a>
                    <form action="{{ route('series.destroy', $serie->id) }}" method="post" class="ms-2">
                        @csrf
                        @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                        Excluir
                    </button>
                    </form>
                </span>
            </li>
        @endforeach
    </ul>
</x-layout>

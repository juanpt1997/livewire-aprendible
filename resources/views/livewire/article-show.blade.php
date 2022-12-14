<div>
    <div>{{ $article->id }}</div>
    <h1>{{ $article->title }}</h1>
    <p>{{ $article->content }}</p>
    <a href="{{ route('articles.index') }}">Regresar</a>
</div>

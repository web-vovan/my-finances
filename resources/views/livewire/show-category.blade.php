<div>
    <a class="waves-effect waves-light btn">button</a>
    @foreach ($categories as $category)
        <p>{{ $category->name }}</p>
    @endforeach
</div>

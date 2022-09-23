<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @foreach ($productos as $item)
            <p>Id: {{ $item->id }}</p>
        @endforeach
    </div>
</div>

<h3>Products</h3>

@foreach($products as $p)
    <div>
        <h4>{{ $p->name }}</h4>
        <p>₹{{ $p->price }}</p>

        <a href="/product/{{ $p->id }}">View</a>
    </div>
@endforeach
<h3>{{ $product->name }}</h3>
<h4>₹{{ $product->price }}</h4>

<form action="/pay" method="POST">
    @csrf

    <input type="hidden" name="amount" value="{{ $product->price }}">
    <input type="hidden" name="name" value="{{ $product->name }}">

    <h5>Select Payment</h5>

    <input type="radio" name="gateway" value="stripe" required> Stripe <br>
    <input type="radio" name="gateway" value="paypal"> PayPal <br>
    <input type="radio" name="gateway" value="razorpay"> Razorpay <br>
    <input type="radio" name="gateway" value="payu"> PayU <br>

    <br>
    <button class="btn btn-success">Pay Now</button>
</form>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<button id="payBtn">Pay with Razorpay</button>

<script>
var options = {
    "key": "{{ env('RAZORPAY_KEY') }}",
    "amount": "{{ $amount * 100 }}",
    "currency": "INR",
    "name": "{{ $name }}",

    "handler": function (response){
        alert("Payment Successful");
        window.location.href = "/success";
    }
};

var rzp = new Razorpay(options);

document.getElementById('payBtn').onclick = function(e){
    rzp.open();
    e.preventDefault();
}
</script>
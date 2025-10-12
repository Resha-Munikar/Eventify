<form action="https://esewa.com.np/epay/main" method="POST" id="esewaForm">
    <input value="{{ $amount }}" name="tAmt" type="hidden">
    <input value="{{ $amount }}" name="amt" type="hidden">
    <input value="0" name="txAmt" type="hidden">
    <input value="0" name="psc" type="hidden">
    <input value="0" name="pdc" type="hidden">
    <input value="{{ $event->id }}" name="pid" type="hidden">
    <input value="{{ env('ESEWA_MERCHANT_CODE') }}" name="scd" type="hidden">
    <input value="{{ $successUrl }}" type="hidden" name="su">
    <input value="{{ $failUrl }}" type="hidden" name="fu">
</form>

<script>
    document.getElementById('esewaForm').submit();
</script>

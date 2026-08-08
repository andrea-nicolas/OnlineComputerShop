

function decrement(button)
{
    let qty = button.closest('.qty').querySelector('.qty-input');
    if (qty.value <= 0)
    {
        qty.value = 0;
    }
    else
    {
        qty.value = parseInt(qty.value) - 1;
    }
}

function increment(button)
{
    let qty = button.closest('.qty').querySelector('.qty-input');
    qty.value = parseInt(qty.value) + 1;
}
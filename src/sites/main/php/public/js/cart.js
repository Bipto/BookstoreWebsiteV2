async function AddToCart(id) {
  try {
    const url = '/ajax/cart/add_book_to_cart.php?book_id=' + id;
    const response = await fetch(url, {method: 'GET'});

    if (!response.ok) {
      throw new Error('Request failed');
    }

    window.location.href = '/';
  } catch (error) {
    document.querySelector('.page').innerHTML = '<p>Something went wrong.</p>';
  }
}

async function RemoveFromCart(id, event) {
  event.stopPropagation();

  try {
    // Remove the item from the cart
    const removeResponse = await fetch(
        `/ajax/cart/remove_item_from_cart.php?id=${encodeURIComponent(id)}`);

    if (!removeResponse.ok) {
      throw new Error(`Failed to remove item: ${removeResponse.status}`);
    }

    // Fetch the updated cart HTML
    const cartResponse = await fetch('/ajax/cart/get_cart.php');

    if (!cartResponse.ok) {
      throw new Error(`Failed to refresh cart: ${cartResponse.status}`);
    }

    const html = await cartResponse.text();

    // Update the cart content
    document.getElementById('cart-content').innerHTML = html;

    // Update the cart count
    const cartCount = document.getElementById('cart-count');
    const count = Math.max(0, parseInt(cartCount.textContent, 10) - 1);

    cartCount.textContent = count;
    cartCount.style.display = count === 0 ? 'none' : '';

  } catch (error) {
    console.error('Error updating cart:', error);
  }
}
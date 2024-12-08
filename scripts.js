let startX, endX;
let menuOpen = false;
let order = [];

// Track touch swipe to open/close menus
document.addEventListener('touchstart', function(event) {
    startX = event.touches[0].clientX;
}, false);

document.addEventListener('touchmove', function(event) {
    endX = event.touches[0].clientX;
}, false);

document.addEventListener('touchend', function(event) {
    if (startX > endX + 20) {
        if (menuOpen) {
            closeMenus();
        } else {
            openMenu('right');
        }
    } else if (startX < endX - 10) {
        if (menuOpen) {
            closeMenus();
        } else {
            openMenu('left');
        }
    }
}, false);

function openMenu(menu) { //might change the whole menu thing
    closeMenus();
    if (menu === 'left') {
        document.getElementById('left-menu').classList.add('active');
    } else if (menu === 'right') {
        document.getElementById('right-menu').classList.add('active');
    }
    menuOpen = true;
}

function closeMenus() {
    document.getElementById('left-menu').classList.remove('active');
    document.getElementById('right-menu').classList.remove('active');
    menuOpen = false;
}

// Function to handle drink selection
function selectDrink(drinkId, drinkName) {
    document.getElementById('selected_drink_id').value = drinkId;
    document.getElementById('selected_drink_name').value = drinkName;
    openMenu('right'); // Automatically open right menu (ingredients) on drink selection
}

// Function to add selected drink and ingredients to the order
function addToOrder() {
    const formData = new FormData(document.getElementById('orderForm'));
    const drinkId = formData.get('drink_id');
    const drinkName = formData.get('drink_name');
    const ingredients = formData.getAll('ingredients[]').join(', ');
    const whippedCream = formData.get('whipped_cream');
    const sugarLevel = formData.get('sugar_level');
    const caffeine = formData.get('caffeine');

    const orderItem = {
        drink_id: drinkId,
        drink_name: drinkName,
        ingredients: ingredients,
        whipped_cream: whippedCream,
        sugar_level: sugarLevel,
        caffeine: caffeine
    };

    order.push(orderItem);
    alert(`${drinkName} added to your order!`);

    closeMenus(); // Close the right menu after adding the order
}

// Function to confirm and submit the entire order
function confirmOrder() {
    if (order.length === 0) {
        alert('Your order is empty!');
        return;
    }

    // Send order to the server
    fetch('confirm_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(order)
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Your order has been confirmed!');
            window.location.href = 'order_confirmed.php';
        } else {
            alert('There was an error confirming your order. Please try again.');
        }
    });
}
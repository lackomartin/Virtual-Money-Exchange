function httpRequest(value, className, method, src) {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(className).innerHTML = this.responseText;
        }
    }
    xmlhttp.open(method, src+value, true);
    xmlhttp.send();
}

function closeWindow() {
    const closeBtn = document.querySelector('.close');
    closeBtn.addEventListener('click', function() {
        document.querySelector('.background').classList.remove('show');
        const audio = document.getElementById('audio');
        audio.pause();
    });
}

closeWindow();

function openWindow(array, className) {
    array.forEach(element => {
        element.addEventListener('click', function() {
            document.querySelector('.background').classList.add('show');
            document.querySelector(className).classList.add('show');

            /* update currency */
            const value = element.firstElementChild.innerHTML;
            httpRequest(value, '.update-currency-form', 'GET', 'update_currency.php?name=');

            /* update wallet */
            const wallet = element.firstElementChild.innerHTML;
            httpRequest(wallet, '.update-amount-form', 'GET', 'update_wallet.php?amount=');

            /* show currency info */
            const currency = element.firstElementChild.innerHTML;
            httpRequest(currency, '.update-rate-form', 'GET', 'show_currency.php?id=');
            httpRequest(currency, '.currency-info-index', 'GET', 'show_info_index.php?id=');

            /* update users */
            const users = element.firstElementChild.innerHTML;
            httpRequest(users, '.update-user-form', 'GET', 'update_users.php?user=');

        });
    });
}

function getElements() {
    /* Show currency info */
    const currencyItems = [].slice.call(document.querySelectorAll('.item'));
    openWindow(currencyItems, '.currency-info-container');

    /* Update amount */
    const amountItems = [].slice.call(document.querySelectorAll('.my-amount'));
    openWindow(amountItems, '.update-wallet-box');

    /* Update currency */
    const updateCurrencyItems = [].slice.call(document.querySelectorAll('.currency'));
    openWindow(updateCurrencyItems, '.update-currency-box');

    /* Update user info */
    const usersItems = [].slice.call(document.querySelectorAll('.user'));
    openWindow(usersItems, '.update-user-box');
    
}

getElements();



let rate1;
let rate2;

function getSelectedRate() {
    let selectBoxExchange = document.getElementById('exchange');
    rate1 = selectBoxExchange[selectBoxExchange.selectedIndex].id;
    
    let selectBoxRecieve = document.getElementById('recieve');
    selectBoxRecieve = selectBoxRecieve[selectBoxRecieve.selectedIndex].id;

    let recieveRate = document.getElementById('selected-rate');
    recieveRate.innerHTML = selectBoxRecieve
    rate2 = selectBoxRecieve;
}

getSelectedRate();

function getSelectedCurrency() {
    let currency = document.getElementById('exchange');
    let currencyRecieve = document.getElementById('recieve');
    currency = currency[currency.selectedIndex].innerHTML;
    currencyRecieve = currencyRecieve[currencyRecieve.selectedIndex].innerHTML;
    
    const exchangeCurrency = document.getElementById('exchange-currency');
    exchangeCurrency.innerHTML = currency;

    const recieveCurrencxy = document.getElementById('recieve-currency');
    recieveCurrencxy.innerHTML = currencyRecieve;
}

getSelectedCurrency();

function calculateAmount(inputAmount, rate1, rate2) {
    const recieveAmount = document.getElementById('recieve-amount');

        const calcAmount = (inputAmount * rate1)/rate2;
        recieveAmount.innerHTML = calcAmount.toFixed(2);
}

function userRequest() {
    const userAmount = document.getElementById('user-amount');
    const exchangeAmount = document.getElementById('exchange-amount');

    const inputHandler = function(e) {
        exchangeAmount.innerHTML = e.target.value;
        exchangeAmount.value = e.target.value;

        /* Calculate amount - input change */
        const inputAmount = exchangeAmount.innerHTML
        calculateAmount(inputAmount, rate1, rate2);
    }

    userAmount.addEventListener('input', inputHandler);

    /* Calculate amount - rate change */
    const inputAmount = exchangeAmount.innerHTML
    calculateAmount(inputAmount, rate1, rate2);

}

userRequest();
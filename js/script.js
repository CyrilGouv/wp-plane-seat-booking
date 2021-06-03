
class SeatBooking {
    constructor() {
        // Elements
        this.form = document.getElementById('form-plane-booking')
        this.container = document.querySelector('.container')
        this.seats = document.querySelectorAll('.row .seat:not(.occupied)')
        this.count = document.getElementById('count')
        this.total = document.getElementById('total')
        this.result = document.querySelector('.result')

        // Ajax Url
        this.url = this.form.dataset.url

        this.firstClass = 0
        this.secClass = 0
        this.firstClassPrice = 0
        this.secClassPrice = 0
        this.amountTotal = 0
        
        this.events()
        this.populateUI()
    }

    events() {
        this.container.addEventListener('click', (e) => this.select(e))

        this.form.addEventListener('submit', (e) => this.handleBooking(e))
    }

    select(e) {
        if (e.target.classList.contains('seat') && !e.target.classList.contains('occupied')) {
            // Add class selected to the target
            e.target.classList.toggle('selected')

            // Count number of place selected
            let newCount = document.querySelectorAll('.row .seat.selected').length
            this.count.innerText = newCount

            // Total price
            this.totalPrice(e.target)
        }
    }

    totalPrice(target) {
        if (target.parentElement.parentElement.classList.contains('sec-class')) {
            this.secClass = document.querySelectorAll('.sec-class .row .seat.selected').length
            let priceSelected = +target.parentElement.parentElement.dataset.price
        
            this.secClassPrice = this.secClass * priceSelected
            
        } else {
            this.firstClass = document.querySelectorAll('.first-class .row .seat.selected').length
            let priceSelected = +target.parentElement.parentElement.dataset.price
        
            this.firstClassPrice = this.firstClass * priceSelected
        }
        
        this.total.innerText = this.firstClassPrice + this.secClassPrice
    }

    handleBooking(e) {
        e.preventDefault()

        // Check if at least one seat is selected
        if (this.amountTotal === 0 && this.firstClass === 0 && this.secClass === 0) {
            this.result.innerText = 'Vous devez selectionner au moins un siège'
        }

        // Create "ID" to know the seat place
        const selectedSeats = document.querySelectorAll('.row .seat.selected')
        const seatsIndex = [...selectedSeats].map(seat => {
            return [...this.seats].indexOf(seat)
        })

        const params = JSON.stringify(seatsIndex)

        fetch(this.url, {
            method: 'POST',
            body: params
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                this.result.innerText = res.message
            }
        })
        .catch(err => console.log(err)) 
    }

    populateUI() {
        fetch(this.url, {
            method: 'GET',
        })
        .then(res => res.json())
        .then(res => {

            const allSeats =  res
            if (allSeats !== null && allSeats.length > 0) {
                
                
                console.log(allSeats);
                allSeats.forEach((item) => {
                    const itemNum = +item.seat

                    this.seats[itemNum].classList.add('occupied')
                    
                })
                
            }
        })
        .catch(err => console.log(err)) 
    }
}

new SeatBooking
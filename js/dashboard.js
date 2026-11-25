// Fetch user info
fetch('http://localhost:3000/home', { credentials: 'include' })
.then(res => res.json())
.then(data => {
    if(data.status === 'success') {
        document.getElementById('username').innerText = data.user.name;
        document.getElementById('icr').innerText = data.user.icr;
        document.getElementById('isf').innerText = data.user.isf;
    } else {
        window.location.href = 'index.html';
    }
});

// Logout
document.getElementById('logout').addEventListener('click', () => {
    fetch('http://localhost:3000/logout', { method: 'POST', credentials: 'include' })
    .then(() => {
        Swal.fire('Logged Out','You have been logged out','success')
        .then(()=> window.location.href='index.html');
    });
});

// Sample Chart using Chart.js
const ctx = document.getElementById('chart1').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Glucose Level',
            data: [120, 110, 115, 130, 125, 118, 122],
            backgroundColor: 'rgba(106,17,203,0.2)',
            borderColor: 'rgba(106,17,203,1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: false } }
    }
});

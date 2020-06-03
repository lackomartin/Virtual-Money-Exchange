let recieved, rejected, onHold;

function getNumberOfRequests() {
  recieved = document.getElementById('recieved').innerHTML;
  rejected = document.getElementById('rejected').innerHTML;
  onHold = document.getElementById('onHold').innerHTML;
}

getNumberOfRequests();

new Chart(document.getElementById("doughnut-chart"), {
    type: 'doughnut',
    data: {
      labels: ["Recieved", "Rejected", "On hold"],
      datasets: [
        {
          label: "Requests",
          backgroundColor: ["#1dd1a1", "#ee5253","#c8d6e5"],
          data: [recieved, rejected, onHold]
        }
      ]
    },
    options: {
      title: {
        display: true,
        text: 'My requests (recieved/rejected/on hold)'
      }
    }
});
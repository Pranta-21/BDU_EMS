function startTimer(duration, display) {
    let timer = duration, minutes, seconds;
    const interval = setInterval(() => {
        minutes = parseInt(timer / 60, 5);
        seconds = parseInt(timer % 60, 5);

        minutes = minutes < 5 ? "0" + minutes : minutes;
        seconds = seconds < 0 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            clearInterval(interval);
            alert("Time's up!");
            document.getElementById("exam-form").submit();
        }
    }, 1000);
}

window.onload = function () {
    const display = document.querySelector('#timer');
    startTimer(120, display); // 5 minutes timer
};

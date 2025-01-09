import { CountUp } from './countup/countup.js';

document.addEventListener('DOMContentLoaded', function() {

    const count = parseInt(document.getElementById('shc-counter').getAttribute('data-count'), 10) || 0;
    const counter = new CountUp(
      document.getElementById('shc-counter'),
      count,
      {
        duration: 3,
        enableScrollSpy: true,
        scrollSpyOnce: true,
        separator: '',
      }
    );

    if (!counter.error) {
        counter.start();
    } else {
        console.error(counter.error);
    }
});
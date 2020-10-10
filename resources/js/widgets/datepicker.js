import Litepicker from 'litepicker';

const datePickers = document.querySelectorAll('.datepicker:not([readonly])');

window.disableLitepickerStyles = true;
if (datePickers) {
    const dateFormat = 'DD/MM/YYYY';
    const fullDateFormat = 'DD MMMM YYYY';
    datePickers.forEach(datePicker => {
        const options = {
            element: datePicker,
            singleMode: true,
            numberOfMonths: 1,
            numberOfColumns: 1,
            format: fullDateFormat,
        }
        if (datePicker.dataset.minDate) {
            options.minDate = datePicker.dataset.minDate;
        }
        if (datePicker.dataset.maxDate) {
            options.maxDate = datePicker.dataset.maxDate;
        }
        if (datePicker.dataset.parentEl) {
            options.parentEl = datePicker.dataset.parentEl;
        }
        const picker = new Litepicker(options);
    });
}

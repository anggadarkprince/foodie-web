const inputFiles = document.querySelectorAll('.input-file');
inputFiles.forEach(inputFile => {
    inputFile.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const inputWrapper = inputFile.closest('.input-file-wrapper');
            if (inputWrapper) {
                const inputFileWrapper = inputWrapper.querySelector('.input-file-label');
                inputFileWrapper.value = this.files[0].name;
            }
        }
    });
})

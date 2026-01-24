<x-layout>
    @livewire('keluar-form', ['model' => $model])

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Select the first element with the class 'autofocus-target'
            const elementToFocus = document.querySelector('.focus');

            // If the element is found, apply focus
            if (elementToFocus) {
                elementToFocus.focus();
            }
        });
    </script>

</x-layout>
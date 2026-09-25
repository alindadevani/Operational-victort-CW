@props(['name', 'label'])

@php
    $canvasId = $name . '_canvas';
    $inputId = $name . '_input';
    $clearId = $name . '_clear';
@endphp

<div>
    <div class="flex items-center justify-between mb-1">
        <label for="{{ $canvasId }}" class="block text-sm font-medium text-gray-700">
            {{ $label }} <span class="text-red-500">*</span>
        </label>
        <button type="button" id="{{ $clearId }}"
            class="px-2.5 py-1 text-xs text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            Hapus
        </button>
    </div>

    <canvas id="{{ $canvasId }}" width="600" height="150"
        class="block w-full h-[150px] bg-white border border-dashed border-gray-300 rounded-lg touch-none {{ $errors->has($name) ? 'border-red-400' : '' }}"></canvas>
    <input type="hidden" name="{{ $name }}" id="{{ $inputId }}" value="{{ old($name) }}">

    @error($name)
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('{{ $canvasId }}');
        const input = document.getElementById('{{ $inputId }}');
        const clearButton = document.getElementById('{{ $clearId }}');
        const context = canvas.getContext('2d');
        let isDrawing = false;

        context.strokeStyle = '#1f2937';
        context.lineWidth = 2;
        context.lineCap = 'round';
        context.lineJoin = 'round';

        function getPosition(event) {
            const point = event.touches ? event.touches[0] : event;
            const bounds = canvas.getBoundingClientRect();

            return {
                x: (point.clientX - bounds.left) * (canvas.width / bounds.width),
                y: (point.clientY - bounds.top) * (canvas.height / bounds.height)
            };
        }

        function startDrawing(event) {
            event.preventDefault();
            isDrawing = true;
            const position = getPosition(event);
            context.beginPath();
            context.moveTo(position.x, position.y);
        }

        function draw(event) {
            if (!isDrawing) {
                return;
            }

            event.preventDefault();
            const position = getPosition(event);
            context.lineTo(position.x, position.y);
            context.stroke();
        }

        function stopDrawing(event) {
            if (!isDrawing) {
                return;
            }

            event.preventDefault();
            isDrawing = false;
            input.value = canvas.toDataURL('image/png');
        }

        function clearCanvas() {
            context.clearRect(0, 0, canvas.width, canvas.height);
            input.value = '';
        }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing, { passive: false });
        clearButton.addEventListener('click', clearCanvas);

        if (input.value) {
            const savedSignature = new Image();
            savedSignature.onload = function () {
                context.drawImage(savedSignature, 0, 0, canvas.width, canvas.height);
            };
            savedSignature.src = input.value;
        }
    });
</script>
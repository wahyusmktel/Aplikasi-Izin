<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pindai QR Code Surat Izin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">

                    <div id="reader" class="w-full max-w-sm mx-auto border-4 border-dashed rounded-lg"></div>

                    <div id="result" class="mt-4 font-mono text-sm"></div>

                    <div class="mt-6">
                        <x-primary-button id="startButton">Mulai Pindai</x-primary-button>
                        <x-secondary-button id="stopButton" style="display: none;">Hentikan</x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const readerElement = document.getElementById('reader');
                const resultElement = document.getElementById('result');
                const startButton = document.getElementById('startButton');
                const stopButton = document.getElementById('stopButton');

                let html5QrCode;

                function onScanSuccess(decodedText, decodedResult) {
                    // Ketika QR Code berhasil dipindai
                    resultElement.innerHTML =
                        `<span class="text-green-600">Sukses! Mengarahkan ke halaman verifikasi...</span>`;
                    console.log(`Scan result: ${decodedText}`);

                    // Hentikan scanner
                    html5QrCode.stop().then(ignore => {
                        // Redirect ke URL yang ada di QR Code
                        window.location.href = decodedText;
                    }).catch(err => {
                        console.error("Gagal menghentikan scanner.", err);
                        // Tetap redirect meskipun gagal stop
                        window.location.href = decodedText;
                    });
                }

                function onScanFailure(error) {
                    // Tidak melakukan apa-apa saat gagal, agar tidak mengganggu
                    // console.warn(`Code scan error = ${error}`);
                }

                startButton.addEventListener('click', () => {
                    html5QrCode = new Html5Qrcode("reader");
                    html5QrCode.start({
                            facingMode: "environment"
                        }, // Gunakan kamera belakang
                        {
                            fps: 10, // Frame per second
                            qrbox: {
                                width: 250,
                                height: 250
                            } // Ukuran kotak pemindaian
                        },
                        onScanSuccess,
                        onScanFailure
                    ).then(() => {
                        startButton.style.display = 'none';
                        stopButton.style.display = 'inline-flex';
                        resultElement.innerHTML = 'Arahkan kamera ke QR Code...';
                    }).catch(err => {
                        resultElement.innerHTML =
                            `<span class="text-red-600">Error: Tidak dapat memulai kamera. ${err}</span>`;
                    });
                });

                stopButton.addEventListener('click', () => {
                    html5QrCode.stop().then(ignore => {
                        startButton.style.display = 'inline-flex';
                        stopButton.style.display = 'none';
                        resultElement.innerHTML = 'Scanner dihentikan.';
                    }).catch(err => {
                        console.error("Gagal menghentikan scanner.", err);
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>

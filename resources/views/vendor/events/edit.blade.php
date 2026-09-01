@extends('layouts.app')

@section('title', 'Edit Event')
@php 
    $noNavbar = true; 
    $noFooter = true; 

    $initialTickets = old('ticket_types', $event->ticketTypes->map(function($t) {
        return [
            'id' => $t->id,
            'name' => $t->name,
            'price' => (float)$t->price,
            'quantity' => (int)$t->quantity,
            'sold_quantity' => (int)$t->sold_quantity,
            'description' => $t->description ?? '',
            'status' => $t->status ?? 'active',
        ];
    })->toArray());

    if (empty($initialTickets)) {
        $initialTickets = [
            ['id' => null, 'name' => 'General Admission', 'price' => (float)$event->price, 'quantity' => (int)$event->available_seats, 'sold_quantity' => 0, 'description' => '', 'status' => 'active']
        ];
    }
@endphp
@include('vendor.sidebar')

@section('content')
<div class="ml-0 sm:ml-64 p-6 bg-gray-100 dark:bg-gray-900 min-h-screen overflow-x-hidden">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
        <h2 class="text-3xl font-bold text-[#8d85ec] mb-6 text-center">Edit Event</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6 border border-red-300">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vendor.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Event Name -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Event Name <span class="text-red-500">*</span></label>
                <input type="text" name="event_name" value="{{ old('event_name', $event->event_name) }}"
                       class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
            </div>

            <!-- Event Date & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Event Date & Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="event_date" 
                        value="{{ old('event_date', $event->event_date ? $event->event_date->format('Y-m-d\TH:i') : '') }}"
                        class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Category <span class="text-red-500">*</span></label>
                    <select name="category" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                        <option value="" disabled>Select a category</option>
                        <option value="Concert" {{ old('category', $event->category) == 'Concert' ? 'selected' : '' }}>Concert</option>
                        <option value="Art" {{ old('category', $event->category) == 'Art' || old('category', $event->category) == 'Exhibition' ? 'selected' : '' }}>Exhibition / Art</option>
                        <option value="Food & Drink" {{ old('category', $event->category) == 'Food & Drink' ? 'selected' : '' }}>Food & Drink</option>
                        <option value="Technology" {{ old('category', $event->category) == 'Technology' ? 'selected' : '' }}>Technology</option>
                        <option value="Sports" {{ old('category', $event->category) == 'Sports' ? 'selected' : '' }}>Sports</option>
                        <option value="Wellness" {{ old('category', $event->category) == 'Wellness' || old('category', $event->category) == 'Workshop' ? 'selected' : '' }}>Workshop / Wellness</option>
                    </select>
                </div>
            </div>

            <!-- Venue / Location -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Venue / Location <span class="text-red-500">*</span></label>
                <input type="text" name="venue" value="{{ old('venue', $event->venue) }}"
                       class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
            </div>

            <!-- TICKET TYPES SECTION (Alpine.js) -->
            <div x-data="{
                ticketTypes: {{ json_encode($initialTickets) }},
                addTicketType() {
                    this.ticketTypes.push({ id: null, name: '', price: '', quantity: '', sold_quantity: 0, description: '', status: 'active' });
                },
                removeTicketType(index) {
                    let ticket = this.ticketTypes[index];
                    if (ticket.sold_quantity && ticket.sold_quantity > 0) {
                        if (confirm('This ticket type already has ' + ticket.sold_quantity + ' ticket(s) sold. Deleting it will mark it as Inactive instead of deleting historical data. Continue?')) {
                            ticket.status = 'inactive';
                            this.ticketTypes.splice(index, 1);
                        }
                    } else {
                        if (this.ticketTypes.length > 1) {
                            this.ticketTypes.splice(index, 1);
                        } else {
                            alert('At least one ticket type must remain.');
                        }
                    }
                }
            }" class="p-6 bg-purple-50 dark:bg-gray-700 rounded-2xl border-2 border-dashed border-[#8d85ec]/50 space-y-4">
                
                <div class="flex items-center justify-between border-b border-purple-200 dark:border-gray-600 pb-3">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>🎫</span> Manage Ticket Types
                        </h3>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                            Modify prices, increase capacity, add new tiers, or manage existing ticket tiers.
                        </p>
                    </div>
                    <button type="button" @click="addTicketType()" 
                            class="inline-flex items-center gap-1 bg-[#8d85ec] hover:bg-[#7a72d6] text-white px-3 py-1.5 rounded-lg text-sm font-semibold transition shadow-sm">
                        <span>+</span> Add Ticket Type
                    </button>
                </div>

                <!-- Ticket Type Cards -->
                <div class="space-y-4">
                    <template x-for="(ticket, index) in ticketTypes" :key="index">
                        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm relative space-y-3">
                            <input type="hidden" :name="'ticket_types[' + index + '][id]'" x-model="ticket.id">
                            <input type="hidden" :name="'ticket_types[' + index + '][status]'" x-model="ticket.status">

                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-[#8d85ec] uppercase tracking-wider" x-text="'Ticket Option #' + (index + 1)"></span>
                                    <template x-if="ticket.sold_quantity > 0">
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2.5 py-0.5 rounded-full font-bold" x-text="ticket.sold_quantity + ' Sold'"></span>
                                    </template>
                                </div>
                                <button type="button" @click="removeTicketType(index)" x-show="ticketTypes.length > 1"
                                        class="text-red-500 hover:text-red-700 text-xs font-semibold px-2 py-1 bg-red-50 dark:bg-gray-700 rounded transition">
                                    ✕ Remove
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Name -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Ticket Name <span class="text-red-500">*</span></label>
                                    <input type="text" :name="'ticket_types[' + index + '][name]'" x-model="ticket.name" placeholder="e.g. VIP, Regular"
                                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                                </div>

                                <!-- Price -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Price (Rs.) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" min="0" :name="'ticket_types[' + index + '][price]'" x-model="ticket.price" placeholder="500"
                                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                                </div>

                                <!-- Quantity -->
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200">Total Quantity <span class="text-red-500">*</span></label>
                                        <template x-if="ticket.sold_quantity > 0">
                                            <span class="text-[11px] text-amber-600 font-medium" x-text="'Min: ' + ticket.sold_quantity"></span>
                                        </template>
                                    </div>
                                    <input type="number" :min="ticket.sold_quantity > 0 ? ticket.sold_quantity : 1" :name="'ticket_types[' + index + '][quantity]'" x-model="ticket.quantity" placeholder="100"
                                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Description / Perks (Optional)</label>
                                <input type="text" :name="'ticket_types[' + index + '][description]'" x-model="ticket.description" placeholder="e.g. VIP Front row seating + Refreshments"
                                       class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none">
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Description -->
            <div x-data="{ wordCount: {{ str_word_count(old('description', $event->description)) }} }">
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4"
                          maxlength="500" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required @input="wordCount = $event.target.value.trim() ? $event.target.value.trim().split(/\s+/).length : 0">{{ old('description', $event->description) }}</textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400"><span x-text="wordCount"></span>/50 words</p>
            </div>

            <!-- Image Upload (Optional) -->
            <div>
                <div class="flex items-center justify-between gap-3 mb-2">
                    <label class="block font-semibold text-gray-700 dark:text-gray-200">Event Cover Image</label>
                </div>

                <div class="rounded-2xl border border-dashed border-[#8d85ec]/50 bg-purple-50/50 dark:bg-gray-700/80 p-4">
                    <div id="event-image-trigger" class="cursor-pointer">
                        <input id="event-image-input" type="file" name="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden">

                        <div id="event-image-empty" class="flex flex-col items-center justify-center text-center py-6 {{ $event->image ? 'hidden' : '' }}">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-[#8d85ec]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5V7.75A2.75 2.75 0 0 1 5.75 5h12.5A2.75 2.75 0 0 1 21 7.75v8.75a2.75 2.75 0 0 1-2.75 2.75H5.75A2.75 2.75 0 0 1 3 16.5Zm3.5-5.5 2.75 3.25 2.75-3.25 4.75 5.75H7.25l-.75-.75Z"/>
                                </svg>
                            </div>
                            <p class="text-base font-semibold text-gray-800 dark:text-gray-100">Upload Event Cover</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, JPEG, PNG · Max 5 MB</p>
                        </div>

                        <div id="event-image-preview-wrapper" class="{{ $event->image ? '' : 'hidden' }} mx-auto w-full max-w-[300px]">
                            <div class="w-full h-56 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 shadow-sm">
                                <img id="event-image-preview" src="{{ $event->image ? asset('uploads/' . $event->image) : '' }}" alt="Event cover preview" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-center gap-3">
                        <button type="button" id="event-image-change" class="{{ $event->image ? '' : 'hidden' }} rounded-full border border-[#8d85ec] text-[#8d85ec] px-3 py-1.5 text-xs font-semibold hover:bg-[#8d85ec] hover:text-white transition">
                            Change Image
                        </button>
                        <button type="button" id="event-image-remove" class="{{ $event->image ? '' : 'hidden' }} rounded-full border border-red-300 text-red-600 px-3 py-1.5 text-xs font-semibold hover:bg-red-50 transition">
                            Remove
                        </button>
                    </div>
                </div>

                <div class="mt-3 rounded-xl border border-purple-200 bg-purple-50/70 dark:border-gray-600 dark:bg-gray-800/80 p-3 text-sm text-gray-700 dark:text-gray-200">
                    <p class="font-semibold">Event Cover Image</p>
                    <p class="mt-1">Recommended format: JPG, JPEG, or PNG</p>
                    <p>Maximum file size: 5 MB</p>
                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">For best results, upload a landscape image suitable for the event card.</p>
                </div>

                <div id="event-image-error" class="hidden mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700"></div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-center gap-6 pt-4">
                <button type="submit"
                    class="bg-gradient-to-r from-purple-500 to-[#8d85ec] hover:from-purple-600 hover:to-[#7a72d6] text-white px-8 py-3 rounded-full font-bold shadow-lg transition transform hover:-translate-y-0.5">
                    Update Event & Tickets
                </button>

                <a href="{{ route('vendor.events.index') }}"
                    class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-8 py-3 rounded-full font-bold transition inline-flex items-center justify-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />

<div id="crop-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-6">
    <div class="w-full max-w-xl rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-2xl sm:p-6">
        <div class="flex items-center justify-between gap-3">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Adjust Event Cover</h3>
            <button type="button" id="crop-modal-close" class="rounded-full p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700 dark:hover:text-white">✕</button>
        </div>

        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Adjust the image so the important content stays inside the frame.</p>

        <div class="mt-5 rounded-2xl border border-gray-200 bg-gray-100/90 p-3 dark:border-gray-600 dark:bg-gray-700/80">
            <div class="mx-auto w-full max-w-[420px]">
                <img id="crop-image" class="mx-auto max-h-[60vh] w-full rounded-xl bg-white dark:bg-gray-800" alt="Crop preview">
            </div>
        </div>

        <div class="mt-5">
            <label for="crop-zoom" class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200">Zoom</label>
            <input id="crop-zoom" type="range" min="1" max="3" step="0.01" value="1" class="w-full accent-[#8d85ec]">
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" id="crop-cancel" class="rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                Cancel
            </button>
            <button type="button" id="crop-use-image" class="rounded-full bg-[#8d85ec] px-5 py-2 text-sm font-semibold text-white hover:bg-[#7a72d6] transition">
                Use Image
            </button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('event-image-input');
        const trigger = document.getElementById('event-image-trigger');
        const emptyState = document.getElementById('event-image-empty');
        const previewWrapper = document.getElementById('event-image-preview-wrapper');
        const preview = document.getElementById('event-image-preview');
        const errorBox = document.getElementById('event-image-error');
        const changeBtn = document.getElementById('event-image-change');
        const removeBtn = document.getElementById('event-image-remove');
        const cropModal = document.getElementById('crop-modal');
        const cropImage = document.getElementById('crop-image');
        const zoomInput = document.getElementById('crop-zoom');
        const closeModalBtn = document.getElementById('crop-modal-close');
        const cancelCropBtn = document.getElementById('crop-cancel');
        const useCropBtn = document.getElementById('crop-use-image');

        let selectedFile = null;
        let cropper = null;
        let previousPreviewDataUrl = preview.src && preview.src !== window.location.href ? preview.src : null;
        let zoomBounds = {
            min: 1,
            max: 3,
            initial: 1,
        };

        function clampZoom(value) {
            return Math.min(Math.max(value, zoomBounds.min), zoomBounds.max);
        }

        function showError(message) {
            errorBox.textContent = message;
            errorBox.classList.remove('hidden');
        }

        function clearError() {
            errorBox.textContent = '';
            errorBox.classList.add('hidden');
        }

        function updatePreviewState(hasImage) {
            emptyState.classList.toggle('hidden', hasImage);
            previewWrapper.classList.toggle('hidden', !hasImage);
            changeBtn.classList.toggle('hidden', !hasImage);
            removeBtn.classList.toggle('hidden', !hasImage);
        }

        function setPreviewImage(dataUrl) {
            preview.src = dataUrl;
            updatePreviewState(Boolean(dataUrl));
        }

        function restorePreviousPreview() {
            if (previousPreviewDataUrl) {
                setPreviewImage(previousPreviewDataUrl);
                return;
            }
            setPreviewImage('');
        }

        function createFileFromCanvas(canvas, originalName) {
            const dataUrl = canvas.toDataURL('image/jpeg', 0.92);
            const blob = dataURLtoBlob(dataUrl);
            const fileName = (originalName || 'event-cover.jpg').replace(/\.[^.]+$/, '.jpg');
            const file = new File([blob], fileName, {
                type: 'image/jpeg',
                lastModified: Date.now(),
            });
            return file;
        }

        function dataURLtoBlob(dataURL) {
            const parts = dataURL.split(',');
            const mime = parts[0].match(/:(.*?);/)[1];
            const binary = atob(parts[1]);
            const array = new Uint8Array(binary.length);
            for (let i = 0; i < binary.length; i++) {
                array[i] = binary.charCodeAt(i);
            }
            return new Blob([array], { type: mime });
        }

        function closeCropModal() {
            cropModal.classList.add('hidden');
            cropModal.classList.remove('flex');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            cropImage.src = '';
            zoomBounds = { min: 1, max: 3, initial: 1 };
            zoomInput.value = '1';
            zoomInput.min = '1';
            zoomInput.max = '3';
            zoomInput.step = '0.01';
        }

        function validateFile(file) {
            if (!file) {
                return false;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            const fileName = (file.name || '').toLowerCase();
            const isValidType = allowedTypes.includes(file.type) || /\.(jpg|jpeg|png)$/i.test(fileName);

            if (!isValidType) {
                showError('Please upload a JPG, JPEG, or PNG image.');
                return false;
            }

            if (file.size > 5 * 1024 * 1024) {
                showError('Image size must be 5 MB or less.');
                return false;
            }

            return true;
        }

        const CROP_ASPECT_RATIO = 16 / 9;

        function openCropModal(file) {
            selectedFile = file;
            const objectUrl = URL.createObjectURL(file);
            cropImage.src = objectUrl;
            cropModal.classList.remove('hidden');
            cropModal.classList.add('flex');

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(cropImage, {
                aspectRatio: CROP_ASPECT_RATIO,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
                responsive: true,
                movable: true,
                zoomable: true,
                scalable: true,
                rotatable: false,
                cropBoxResizable: false,
                minContainerWidth: 260,
                minContainerHeight: 150,
                minCropBoxWidth: 260,
                minCropBoxHeight: 146,
            });

            cropper.on('ready', function () {
                const imageData = cropper.getImageData();
                const cropBox = cropper.getCropBoxData();
                const naturalWidth = imageData.naturalWidth || imageData.width || 1;
                const naturalHeight = imageData.naturalHeight || imageData.height || 1;
                const fitZoom = Math.max(
                    (cropBox.width / naturalWidth) * 1.08,
                    (cropBox.height / naturalHeight) * 1.08,
                    1
                );
                const minZoom = Number(fitZoom.toFixed(2));
                const maxZoom = Math.min(Number((minZoom * 2.5).toFixed(2)), 3);

                zoomBounds = {
                    min: minZoom,
                    max: maxZoom,
                    initial: minZoom,
                };

                zoomInput.min = String(minZoom);
                zoomInput.max = String(maxZoom);
                zoomInput.step = '0.01';
                zoomInput.value = String(minZoom);
                cropper.zoomTo(minZoom);
            });

            const cropperCanvas = cropper.getCropperCanvas();
            if (cropperCanvas) {
                cropperCanvas.scaleStep = 0.06;
            }

            cropper.on('zoom', function () {
                if (!cropper || !zoomInput) {
                    return;
                }

                const currentZoom = clampZoom(Number(cropper.getZoom()));
                zoomInput.value = currentZoom.toFixed(2);
            });
        }

        function useCroppedImage() {
            if (!cropper || !selectedFile) {
                return;
            }

            const canvas = cropper.getCroppedCanvas({
                width: 1280,
                height: 720,
                imageSmoothingQuality: 'high',
            });

            const finalFile = createFileFromCanvas(canvas, selectedFile.name);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(finalFile);
            input.files = dataTransfer.files;

            previousPreviewDataUrl = canvas.toDataURL('image/jpeg', 0.92);
            setPreviewImage(previousPreviewDataUrl);
            clearError();
            closeCropModal();
        }

        trigger.addEventListener('click', function () {
            input.click();
        });

        changeBtn.addEventListener('click', function () {
            input.click();
        });

        removeBtn.addEventListener('click', function () {
            input.value = '';
            previousPreviewDataUrl = null;
            setPreviewImage('');
            clearError();
        });

        input.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) {
                return;
            }

            if (!validateFile(file)) {
                this.value = '';
                return;
            }

            clearError();
            openCropModal(file);
            this.value = '';
        });

        closeModalBtn.addEventListener('click', function () {
            restorePreviousPreview();
            closeCropModal();
        });

        cancelCropBtn.addEventListener('click', function () {
            restorePreviousPreview();
            closeCropModal();
        });

        useCropBtn.addEventListener('click', function () {
            useCroppedImage();
        });

        zoomInput.addEventListener('input', function () {
            if (!cropper) {
                return;
            }

            const zoomValue = Number.parseFloat(this.value);
            if (Number.isFinite(zoomValue)) {
                cropper.zoomTo(clampZoom(zoomValue));
            }
        });

        updatePreviewState(Boolean(preview.src && preview.src !== window.location.href));
    });
</script>
@endsection

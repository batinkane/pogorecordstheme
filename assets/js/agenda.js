(function ($) {
    if (typeof PogoStudiosData === 'undefined') {
        return;
    }

    const calendarGrid = $('#agenda-calendar-grid');
    const tooltipEl = $('#agenda-tooltip');
    const monthSelect = $('#agenda-month-select');
    const yearSelect = $('#agenda-year-select');
    const prevBtn = $('#agenda-prev-month');
    const nextBtn = $('#agenda-next-month');
    const slotsWrapper = $('#agenda-slots');
    const slotsTitle = $('#agenda-slots-title');
    const slotsList = $('#agenda-slots-list');
    const formWrapper = $('#agenda-form-wrapper');
    const bookingForm = $('#agenda-booking-form');
    const bookingStatus = $('#agenda-booking-status');
    const dayInfo = $('#agenda-day-info');
    const resetBtn = $('#agenda-reset-selection');
    const dateDisplayInput = $('#agenda-date-display');
    const timeDisplayInput = $('#agenda-time-display');
    const hiddenDateInput = $('#agenda-form-date');
    const hiddenTimeInput = $('#agenda-form-time');

    const blackoutDates = PogoStudiosData.availability.blackoutDates || [];
    const timeSlots = PogoStudiosData.availability.timeSlots || [];

    let selectedDate = '';
    let selectedSlot = '';
    let visibleMonth = new Date().getMonth();
    let visibleYear = new Date().getFullYear();

    const cachedDayData = {};

    const months = [
        'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran',
        'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'
    ];

    const initSelects = () => {
        months.forEach((label, index) => {
            monthSelect.append(`<option value="${index}">${label}</option>`);
        });
        const currentYear = new Date().getFullYear();
        for (let year = currentYear; year <= currentYear + 2; year++) {
            yearSelect.append(`<option value="${year}">${year}</option>`);
        }
        monthSelect.val(visibleMonth);
        yearSelect.val(visibleYear);
    };

    const formatDateLabel = (dateStr) => {
        if (!/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/.test(dateStr)) {
            return dateStr;
        }
        const [year, month, day] = dateStr.split('-').map(Number);
        const date = new Date(year, month - 1, day);
        const monthNames = [
            'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran',
            'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'
        ];
        return `${String(day).padStart(2, '0')} ${monthNames[month - 1]} ${year}`;
    };

    const isDateInPast = (date) => {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return date < today;
    };

    const isBlackout = (dateStr) => blackoutDates.includes(dateStr);

    const formatIsoDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const dayKey = (date) => formatIsoDate(date);

    const showTooltip = (content, rect) => {
        if (!tooltipEl.length) {
            return;
        }
        const calendarRect = calendarGrid.closest('.agenda-calendar')[0].getBoundingClientRect();
        tooltipEl.html(content);
        const offsetTop = rect.top - calendarRect.top;
        const offsetLeft = rect.left - calendarRect.left + rect.width / 2;
        tooltipEl.css({
            top: offsetTop,
            left: offsetLeft,
            transform: 'translate(-50%, -120%)'
        });
        tooltipEl.addClass('show');
    };

    const hideTooltip = () => tooltipEl.removeClass('show');

    const fetchDayAvailability = (dateStr) => {
        if (cachedDayData[dateStr]) {
            return Promise.resolve(cachedDayData[dateStr]);
        }
        return fetch(`${PogoStudiosData.restUrl}?date=${dateStr}`)
            .then((res) => res.json())
            .then((data) => {
                cachedDayData[dateStr] = data;
                return data;
            })
            .catch(() => {
                cachedDayData[dateStr] = { taken: [] };
                return cachedDayData[dateStr];
            });
    };

    const renderSlots = (dateStr) => {
        slotsList.empty();
        slotsWrapper.removeClass('d-none');
        slotsTitle.text(formatDateLabel(dateStr));

        fetchDayAvailability(dateStr).then((data) => {
            const taken = new Set(data.taken || []);
            timeSlots.forEach((slot) => {
                const isTaken = taken.has(slot);
                const slotEl = $(`
                    <div class="agenda-slot ${isTaken ? 'disabled' : 'available'}" data-slot="${slot}">
                        <span>${slot}</span>
                        <span class="status ${isTaken ? 'status-disabled' : 'status-available'}">
                            ${isTaken ? 'DOLU' : 'MÜSAİT'}
                        </span>
                    </div>
                `);
                if (!isTaken) {
                    slotEl.on('click', () => {
                        selectedSlot = slot;
                        slotsList.find('.agenda-slot').removeClass('selected');
                        slotEl.addClass('selected');
                        hiddenDateInput.val(dateStr);
                        hiddenTimeInput.val(slot);
                        if (dateDisplayInput.length) {
                            dateDisplayInput.val(formatDateLabel(dateStr));
                        }
                        if (timeDisplayInput.length) {
                            timeDisplayInput.val(slot);
                        }
                        formWrapper.removeClass('d-none');
                        bookingStatus.removeClass('show success error').text('');
                    });
                }
                slotsList.append(slotEl);
            });
        });
    };

    const clearSlotSelection = () => {
        selectedSlot = '';
        hiddenTimeInput.val('');
        slotsList.find('.agenda-slot').removeClass('selected');
        if (timeDisplayInput.length) {
            timeDisplayInput.val('');
        }
    };

    const renderCalendar = () => {
        calendarGrid.empty();
        const firstDay = new Date(visibleYear, visibleMonth, 1);
        const lastDay = new Date(visibleYear, visibleMonth + 1, 0);
        const startIndex = (firstDay.getDay() + 6) % 7; // convert Sunday (0) to end
        const totalCells = Math.ceil((startIndex + lastDay.getDate()) / 7) * 7;

        for (let cell = 0; cell < totalCells; cell++) {
            const dayEl = $('<button type="button" class="agenda-day"></button>');
            const dayNumber = cell - startIndex + 1;
            if (dayNumber > 0 && dayNumber <= lastDay.getDate()) {
                const dateObj = new Date(visibleYear, visibleMonth, dayNumber);
                const dateStr = dayKey(dateObj);
                dayEl.append(`<span>${dayNumber}</span>`);
                const statusEl = $('<small class="agenda-day__status">MÜSAİT</small>');
                dayEl.append(statusEl);

                if (isDateInPast(dateObj) || isBlackout(dateStr)) {
                    dayEl.addClass('disabled');
                    statusEl.text('KAPALI');
                } else {
                    dayEl.on('mouseenter focus', (e) => {
                        fetchDayAvailability(dateStr).then((data) => {
                            const taken = new Set(data.taken || []);
                            const availableCount = timeSlots.filter((slot) => !taken.has(slot)).length;
                            const content = `
                                <strong>${formatDateLabel(dateStr)}</strong><br>
                                ${availableCount} müsait, ${taken.size} dolu
                            `;
                            showTooltip(content, e.currentTarget.getBoundingClientRect());
                        });
                    });
                    dayEl.on('mouseleave blur', hideTooltip);
                    dayEl.on('click', () => {
                        selectedDate = dateStr;
                        selectedSlot = '';
                        calendarGrid.find('.agenda-day').removeClass('selected');
                        dayEl.addClass('selected');
                        renderSlots(dateStr);
                        formWrapper.addClass('d-none');
                        bookingStatus.removeClass('show success error').text('');
                        hiddenDateInput.val(dateStr);
                        hiddenTimeInput.val('');
                        if (dateDisplayInput.length) {
                            dateDisplayInput.val(formatDateLabel(dateStr));
                        }
                        if (timeDisplayInput.length) {
                            timeDisplayInput.val('');
                        }
                        dayInfo.find('h3').text(formatDateLabel(dateStr));
                        dayInfo.find('p').text('Saati seçtikten sonra form açılır.');
                    });
                }
            } else {
                dayEl.addClass('empty');
                dayEl.prop('tabindex', -1);
            }
            calendarGrid.append(dayEl);
        }
    };

    const sanitizePhone = (value) => {
        if (!value) {
            return '';
        }
        const digits = value.replace(/\D/g, '');
        const localDigits = digits.startsWith('90') ? digits.slice(2) : digits.slice(-10);
        return localDigits.length === 10 ? `+90${localDigits}` : '';
    };

    const maskPhoneInput = (value) => {
        const digits = value.replace(/\D/g, '');
        let localDigits;
        if (digits.startsWith('90')) {
            localDigits = digits.slice(2);
        } else if (digits.length > 10) {
            localDigits = digits.slice(-10);
        } else {
            localDigits = digits;
        }
        localDigits = localDigits.slice(0, 10);
        const parts = [
            localDigits.slice(0, 3),
            localDigits.slice(3, 6),
            localDigits.slice(6, 8),
            localDigits.slice(8, 10)
        ];
        return `+90 (${parts[0].padEnd(3, '_')}) ${parts[1].padEnd(3, '_')} ${parts[2].padEnd(2, '_')} ${parts[3].padEnd(2, '_')}`;
    };

    const initForm = () => {
        const emailInput = bookingForm.find('input[name="email"]');
        const phoneInput = bookingForm.find('input[name="phone"]');

        phoneInput.on('input', function () {
            $(this).val(maskPhoneInput($(this).val()));
        });

        bookingForm.on('submit', function (event) {
            event.preventDefault();
            const formData = Object.fromEntries(new FormData(this).entries());

            if (!selectedDate || !selectedSlot) {
                bookingStatus.addClass('error show').removeClass('success').text('Lütfen gün ve saat seçin.');
                return;
            }

            const sanitizedPhone = sanitizePhone(formData.phone);
            if (!sanitizedPhone) {
                bookingStatus.addClass('error show').removeClass('success').text('Telefon numarası eksik veya hatalı.');
                phoneInput.trigger('focus');
                return;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
                bookingStatus.addClass('error show').removeClass('success').text('Lütfen geçerli bir e-posta adresi girin.');
                emailInput.trigger('focus');
                return;
            }

            formData.date = selectedDate;
            formData.time = selectedSlot;
            formData.phone = sanitizedPhone;

            fetch(PogoStudiosData.restUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': PogoStudiosData.nonce,
                },
                body: JSON.stringify(formData),
            })
                .then(async (response) => {
                    const payload = await response.json();
                    if (!response.ok) {
                        throw payload;
                    }
                    bookingStatus
                        .removeClass('error')
                        .addClass('success show')
                        .text('🎉 Rezervasyonunuz alındı!');
                    bookingForm[0].reset();
                    selectedSlot = '';
                    renderSlots(selectedDate);
                    setTimeout(() => bookingStatus.removeClass('show'), 2500);
                })
                .catch((error) => {
                    const message = error?.message || 'Bir hata oluştu. Lütfen tekrar deneyin.';
                    bookingStatus
                        .removeClass('success')
                        .addClass('error show')
                        .text(`⚠️ ${message}`);
                });
        });
    };

    const attachEvents = () => {
        monthSelect.on('change', () => {
            visibleMonth = parseInt(monthSelect.val(), 10);
            renderCalendar();
        });

        yearSelect.on('change', () => {
            visibleYear = parseInt(yearSelect.val(), 10);
            renderCalendar();
        });

        prevBtn.on('click', () => {
            if (visibleMonth === 0) {
                visibleMonth = 11;
                visibleYear -= 1;
            } else {
                visibleMonth -= 1;
            }
            monthSelect.val(visibleMonth);
            yearSelect.val(visibleYear);
            renderCalendar();
        });

        nextBtn.on('click', () => {
            if (visibleMonth === 11) {
                visibleMonth = 0;
                visibleYear += 1;
            } else {
                visibleMonth += 1;
            }
            monthSelect.val(visibleMonth);
            yearSelect.val(visibleYear);
            renderCalendar();
        });

        resetBtn.on('click', () => {
            selectedDate = '';
        selectedSlot = '';
        formWrapper.addClass('d-none');
        slotsWrapper.addClass('d-none');
        bookingStatus.removeClass('show success error').text('');
        dayInfo.find('h3').text('Gün Seçin');
        dayInfo.find('p').text('Takvimden gün seçin.');
        if (dateDisplayInput.length) {
            dateDisplayInput.val('');
        }
        if (timeDisplayInput.length) {
            timeDisplayInput.val('');
        }
        hiddenDateInput.val('');
        hiddenTimeInput.val('');
        clearSlotSelection();
    });
    };

    const init = () => {
        initSelects();
        renderCalendar();
        attachEvents();
        initForm();
    };

    init();
})(jQuery);

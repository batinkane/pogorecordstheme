(function ($) {
    const bookingForm = $('#pogo-booking-form');
    const statusEl = $('#booking-status');
    const slotsWrapper = $('#booking-slots');
    const dateInput = bookingForm.find('input[name="date"]');
    const timeSelect = bookingForm.find('select[name="time"]');
    const emailInput = bookingForm.find('input[name="email"]');
    const phoneInput = bookingForm.find('input[name="phone"]');
    const slotDateLabel = $('#booking-slot-date');

    const availability = PogoStudiosData?.availability || {};
    const blackoutDates = availability.blackoutDates || [];
    const timeSlots = availability.timeSlots || [];

    const COUNTRY_CODE = '90';
    const MAX_LOCAL_DIGITS = 10;
    const MIN_LOCAL_DIGITS = 10;

    let currentTakenSlots = [];
    let selectedSlot = '';
    let selectedDate = '';

    const isBlackout = (date) => blackoutDates.includes(date);
    const isValidEmail = (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

    const formatDateLabel = (dateStr) => {
        if (!dateStr) {
            return 'Bir tarih seçtikten sonra saatler burada listelenir.';
        }
        const date = new Date(dateStr);
        if (Number.isNaN(date.getTime())) {
            return 'Tarih formatı geçersiz.';
        }
        const formatted = new Intl.DateTimeFormat('tr-TR', {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
        }).format(date);
        return `${formatted} için uygun saatler`;
    };

    const updateSlotDateLabel = (dateStr) => {
        if (!slotDateLabel.length) {
            return;
        }
        slotDateLabel.text(formatDateLabel(dateStr));
    };

    const getLocalDigits = (value) => {
        const digits = value.replace(/\D/g, '');
        if (digits.startsWith(COUNTRY_CODE)) {
            return digits.slice(COUNTRY_CODE.length);
        }
        return digits;
    };

    const sanitizePhoneValue = (value) => {
        if (!value) {
            return '';
        }
        const localDigits = getLocalDigits(value).slice(0, MAX_LOCAL_DIGITS);
        return localDigits ? `+${COUNTRY_CODE}${localDigits}` : '';
    };

    const maskPhoneInput = (value) => {
        const localDigits = getLocalDigits(value).slice(0, MAX_LOCAL_DIGITS);
        let output = `+${COUNTRY_CODE}`;
        if (!localDigits.length) {
            return output;
        }
        const groups = [
            localDigits.slice(0, 3),
            localDigits.slice(3, 6),
            localDigits.slice(6, 8),
            localDigits.slice(8, 10),
            localDigits.slice(10, 11),
        ];

        output += ' (' + groups[0];
        if (groups[0].length === 3) {
            output += ')';
        }
        if (groups[1]) {
            output += ' ' + groups[1];
        }
        if (groups[2]) {
            output += ' ' + groups[2];
        }
        if (groups[3]) {
            output += ' ' + groups[3];
        }
        if (groups[4]) {
            output += ' ' + groups[4];
        }
        return output;
    };

    const hasValidPhoneLength = (value) => {
        const length = getLocalDigits(value).length;
        return length >= MIN_LOCAL_DIGITS && length <= MAX_LOCAL_DIGITS;
    };

    const isValidDateValue = (value) => {
        if (!value || !/^\d{4}-\d{2}-\d{2}$/.test(value)) {
            return false;
        }
        const [year, month, day] = value.split('-').map(Number);
        const date = new Date(Date.UTC(year, month - 1, day));
        return (
            !Number.isNaN(date.getTime()) &&
            date.getUTCFullYear() === year &&
            date.getUTCMonth() === month - 1 &&
            date.getUTCDate() === day
        );
    };

    const setStatus = (type, message) => {
        if (!statusEl.length) {
            return;
        }
        statusEl.removeClass('success error show').text('');
        if (!type || !message) {
            return;
        }
        statusEl.addClass(type).addClass('show').text(message);
    };

    const paintSlots = (taken = [], reason = 'booked') => {
        currentTakenSlots = taken;
        const takenSet = new Set(taken);

        slotsWrapper.find('.booking-slot').each(function () {
            const slot = $(this).data('slot');
            const statusTag = $(this).find('.status');

            if (takenSet.has(slot)) {
                $(this).addClass('disabled').removeClass('selected');
                const label = reason === 'blocked' ? 'Kapalı' : 'Dolu';
                statusTag
                    .text(label)
                    .removeClass('status-available status-selected text-success text-muted')
                    .addClass('status-disabled');
            } else {
                $(this).removeClass('disabled');
                const isSelected = selectedSlot && slot === selectedSlot;
                $(this).toggleClass('selected', isSelected);
                if (isSelected) {
                    statusTag
                        .text('SEÇİLDİ')
                        .removeClass('status-available status-disabled text-success text-muted')
                        .addClass('status-selected');
                } else {
                    statusTag
                        .text('MÜSAİT')
                        .removeClass('status-selected status-disabled text-success text-muted')
                        .addClass('status-available');
                }
            }
        });

        timeSelect.find('option').each(function () {
            const slot = $(this).val();
            if (!slot) {
                return;
            }
            $(this).prop('disabled', takenSet.has(slot));
        });
    };

    const clearSlotSelection = () => {
        selectedSlot = '';
        timeSelect.val('');
        slotsWrapper.find('.booking-slot').removeClass('selected');
        slotsWrapper.find('.booking-slot .status')
            .filter(function () {
                return !$(this).closest('.booking-slot').hasClass('disabled');
            })
            .removeClass('status-selected status-disabled text-success text-muted')
            .addClass('status-available')
            .text('MÜSAİT');
    };

    const fetchAvailability = (date) => {
        if (!date) {
            return;
        }
        if (!isValidDateValue(date)) {
            setStatus('error', 'Lütfen geçerli bir tarih seçin.');
            timeSelect.prop('disabled', true);
            updateSlotDateLabel('');
            return;
        }
        clearSlotSelection();
        selectedDate = date;
        updateSlotDateLabel(date);

        if (isBlackout(date)) {
            setStatus('error', 'Bu tarih kapalıdır. Lütfen başka bir gün seçin.');
            selectedSlot = '';
            timeSelect.prop('disabled', true).val('');
            paintSlots(timeSlots, 'blocked');
            return;
        }

        timeSelect.prop('disabled', false);
        setStatus(null, null);
        fetch(`${PogoStudiosData.restUrl}?date=${date}`)
            .then((res) => res.json())
            .then((data) => {
                paintSlots(data.taken || []);
            })
            .catch(() => {
                setStatus('error', 'Slot bilgisi alınamadı.');
            });
    };

    if (bookingForm.length) {
        paintSlots([]);
        updateSlotDateLabel('');

        dateInput.on('change', function () {
            const date = $(this).val();
            clearSlotSelection();
            if (!isValidDateValue(date)) {
                setStatus('error', 'Lütfen geçerli bir tarih seçin.');
                updateSlotDateLabel('');
                return;
            }
            selectedSlot = '';
            fetchAvailability(date);
        });

        timeSelect.on('change', function () {
            selectedSlot = $(this).val();
            paintSlots(currentTakenSlots);
        });

        slotsWrapper.on('click', '.booking-slot', function () {
            if ($(this).hasClass('disabled')) {
                return;
            }
            const slot = $(this).data('slot');
            selectedSlot = slot;
            timeSelect.val(slot);
            paintSlots(currentTakenSlots);
        });

        phoneInput.on('input', function () {
            const masked = maskPhoneInput($(this).val());
            $(this).val(masked);
        });

        bookingForm.on('submit', function (event) {
            event.preventDefault();
            const formData = Object.fromEntries(new FormData(this).entries());

            if (!formData.date) {
                setStatus('error', 'Lütfen bir tarih seçin.');
                return;
            }

            if (!isValidDateValue(formData.date)) {
                setStatus('error', 'Tarih formatı geçersiz. YYYY-AA-GG olarak girin.');
                return;
            }

            if (isBlackout(formData.date)) {
                setStatus('error', 'Seçilen tarih kapalıdır.');
                return;
            }

            if (!isValidEmail(formData.email)) {
                setStatus('error', 'Lütfen geçerli bir e-posta adresi girin.');
                emailInput.trigger('focus');
                return;
            }

            const sanitizedPhone = sanitizePhoneValue(phoneInput.val());
            if (!sanitizedPhone || !hasValidPhoneLength(sanitizedPhone)) {
                setStatus('error', 'Telefon numarası +90 sonrası en fazla 11 haneli olmalıdır.');
                phoneInput.trigger('focus');
                return;
            }

            formData.phone = sanitizedPhone;

            if (selectedSlot) {
                formData.time = selectedSlot;
            }

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
                    setStatus('success', '🎉 Rezervasyonun başarıyla oluşturuldu!');
                    setTimeout(() => {
                        bookingForm[0].reset();
                        selectedSlot = '';
                        paintSlots(currentTakenSlots);
                    }, 1500);
                    fetchAvailability(formData.date);
                })
                .catch((error) => {
                    const message = error?.message || PogoStudiosData.strings.bookingError;
                    setStatus('error', `⚠️ ${message}`);
                });
        });
    }
})(jQuery);

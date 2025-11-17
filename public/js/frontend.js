// public/js/frontend.js
// Frontend interactions: mobile menu toggle, modal rendering, deposit form with fetch (+CSRF), toasts, and graceful fallback

document.addEventListener('DOMContentLoaded', function () {
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');

    mobileToggle?.addEventListener('click', function () {
        sidebar.classList.toggle('open');
    });
});

/* ---------- Helpers ---------- */
function getCsrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute('content') : null;
}

function showToast(message, type = 'info', timeout = 3500) {
    const root = document.getElementById('toastRoot') || document.createElement('div');
    root.id = 'toastRoot';
    root.style.position = 'fixed';
    root.style.right = '16px';
    root.style.bottom = '16px';
    root.style.zIndex = 99999;
    document.body.appendChild(root);

    const t = document.createElement('div');
    t.textContent = message;
    t.style.marginTop = '8px';
    t.style.padding = '10px 14px';
    t.style.borderRadius = '8px';
    t.style.color = '#fff';
    t.style.boxShadow = '0 8px 20px rgba(0,0,0,0.12)';
    t.style.fontWeight = 600;
    t.style.minWidth = '200px';
    t.style.opacity = '0';
    t.style.transition = 'all .18s ease';
    if (type === 'success') t.style.background = '#2e7d32';
    else if (type === 'error') t.style.background = '#b71c1c';
    else t.style.background = '#333';

    root.appendChild(t);
    requestAnimationFrame(() => t.style.opacity = '1');

    setTimeout(() => {
        t.style.opacity = '0';
        setTimeout(() => t.remove(), 220);
    }, timeout);
}

async function postJSON(url, payload) {
    const token = getCsrfToken();
    const headers = { 'Content-Type': 'application/json' };
    if (token) headers['X-CSRF-TOKEN'] = token;

    const res = await fetch(url, {
        method: 'POST',
        headers,
        body: JSON.stringify(payload),
        credentials: 'same-origin',
    });
    return res;
}

/* ---------- Modal & forms ---------- */
function openLockerDetail(id, status) {
    const modalRoot = document.getElementById('modalRoot');
    modalRoot.innerHTML = `
        <div class="modal-backdrop" onclick="closeModal()">
            <div class="modal-card" onclick="event.stopPropagation()" style="max-width:520px;margin:60px auto;background:#fff;padding:18px;border-radius:12px;box-shadow:0 12px 40px rgba(0,0,0,0.12);">
                <header style="display:flex;justify-content:space-between;align-items:center;">
                    <h3>Loker ${id} — Detail</h3>
                    <button onclick="closeModal()" class="btn btn-sm btn-outline">Tutup</button>
                </header>
                <div style="margin-top:12px;">
                    <p><strong>Status:</strong> ${status}</p>
                    <p><strong>Penggunaan:</strong> Contoh: 2 jam 12 menit</p>
                    <p><strong>Catatan:</strong> —</p>
                </div>
                <div style="display:flex;gap:8px;margin-top:14px;justify-content:flex-end;">
                    <button class="btn btn-outline" onclick="closeModal()">Close</button>
                    ${status === 'available' ? `<button class="btn btn-primary" onclick="openDepositModal(${id})">Deposit</button>` : ''}
                </div>
            </div>
        </div>
    `;
}

function openDepositModal(lockerId = null) {
    const modalRoot = document.getElementById('modalRoot');
    modalRoot.innerHTML = `
        <div class="modal-backdrop" onclick="closeModal()">
            <div class="modal-card" onclick="event.stopPropagation()" style="max-width:520px;margin:60px auto;background:#fff;padding:18px;border-radius:12px;box-shadow:0 12px 40px rgba(0,0,0,0.12);">
                <header style="display:flex;justify-content:space-between;align-items:center;">
                    <h3>Deposit ke Loker ${lockerId ?? ''}</h3>
                    <button onclick="closeModal()" class="btn btn-sm btn-outline">Tutup</button>
                </header>
                <form id="depositForm" onsubmit="submitDeposit(event)" style="margin-top:12px;display:flex;flex-direction:column;gap:10px;">
                    <input name="name" placeholder="Nama" required />
                    <input name="phone" placeholder="No. WhatsApp (08...)" required />
                    <textarea name="desc" placeholder="Deskripsi barang" rows="3"></textarea>
                    <input type="hidden" name="locker_id" value="${lockerId ?? ''}">
                    <div style="display:flex;gap:8px;justify-content:flex-end;">
                        <button type="button" class="btn btn-outline" onclick="closeModal()">Batal</button>
                        <button id="depositSubmitBtn" type="submit" class="btn btn-primary">Simpan & Kirim WA</button>
                    </div>
                </form>
            </div>
        </div>
    `;
}

function closeModal() {
    const modalRoot = document.getElementById('modalRoot');
    modalRoot.innerHTML = '';
}

/* ---------- Submit logic (connect to backend when ready) ---------- */
async function submitDeposit(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('depositSubmitBtn');
    if (!form) return;

    // basic front validation (additional rules can be added)
    const name = form.name.value.trim();
    const phone = form.phone.value.trim();
    const desc = form.desc.value.trim();
    const locker_id = form.locker_id ? form.locker_id.value : null;

    if (!name || !phone || !locker_id) {
        showToast('Lengkapi nama, phone, dan pilih loker', 'error');
        return;
    }

    const payload = { name, phone, description: desc, locker_id };

    // disable button + loading state
    btn.disabled = true;
    const originalText = btn.textContent;
    btn.textContent = 'Mengirim...';

    // Attempt to POST to backend endpoint /deposit (adjust path if needed)
    try {
        const res = await postJSON('/deposit', payload);
        if (!res.ok) {
            // If backend not ready (404/500) -> fallback to simulation
            console.warn('Backend response not OK:', res.status);
            showToast('Backend belum siap. Menyimpan secara lokal (simulasi).', 'info', 3000);
            // Simulate success behavior (you can customize)
            console.log('Simulated deposit payload:', payload);
            showToast('Penitipan tersimpan (simulasi).', 'success');
            closeModal();
        } else {
            const json = await res.json().catch(() => null);
            // Expect backend returns JSON { success: true, message: '', data: {...} }
            if (json && (json.success === true || res.status === 200)) {
                showToast(json.message || 'Penitipan berhasil dikirim', 'success');
                closeModal();
            } else {
                // Unexpected JSON — still show message
                showToast((json && json.message) ? json.message : 'Terjadi respons tak terduga', 'error');
            }
        }
    } catch (err) {
        // Network error or CORS -> fallback
        console.error('Error submitDeposit:', err);
        showToast('Tidak dapat terhubung ke server. Mode simulasi aktif.', 'info');
        console.log('Simulated deposit payload:', payload);
        showToast('Penitipan tersimpan (simulasi).', 'success');
        closeModal();
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
}
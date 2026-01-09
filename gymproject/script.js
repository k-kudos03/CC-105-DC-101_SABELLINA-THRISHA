const loadProfile = () => {
    fetch('api.php?action=get_profile').then(r => r.json()).then(data => {
        if(data.member_id) {
            document.getElementById('auth-section').style.display = 'none';
            document.getElementById('dashboard-section').style.display = 'block';
            document.getElementById('userName').innerText = data.full_name;
            document.getElementById('days').innerText = data.total_days;
            document.getElementById('fee').innerText = `₱${parseFloat(data.monthly_fee).toLocaleString()}`;
            document.getElementById('tier').innerText = data.plan_name;
        }
    });
};

const handleForm = (e, action) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    fd.append('action', action);
    fetch('api.php', { method: 'POST', body: fd }).then(r => r.json()).then(data => {
        if(data.status === 'success') loadProfile(); else alert(data.message);
    });
};

const checkIn = () => {
    fetch('api.php?action=check_in').then(r => r.json()).then(data => {
        const msg = document.getElementById('msg');
        msg.innerText = data.message;
        msg.style.color = data.status === 'success' ? 'green' : 'red';
        if(data.status === 'success') loadProfile();
    });
};

const toggleAuth = (isReg) => {
    document.getElementById('login-form').style.display = isReg ? 'none' : 'block';
    document.getElementById('reg-form').style.display = isReg ? 'block' : 'none';
};

document.getElementById('frmLogin').onsubmit = (e) => handleForm(e, 'login');
document.getElementById('frmReg').onsubmit = (e) => handleForm(e, 'register');
const logout = () => fetch('api.php?action=logout').then(() => location.reload());
window.onload = loadProfile;
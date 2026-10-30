import { createRoot } from 'react-dom/client';
import { useCallback, useEffect, useMemo, useState } from 'react';

const API_BASE = '/api/v1';
const TOKEN_KEY = 'apple-hr-token';

async function apiRequest(path, { token, method = 'GET', body } = {}) {
    const response = await fetch(`${API_BASE}${path}`, {
        method,
        headers: {
            Accept: 'application/json',
            ...(body ? { 'Content-Type': 'application/json' } : {}),
            ...(token ? { Authorization: `Bearer ${token}` } : {}),
        },
        ...(body ? { body: JSON.stringify(body) } : {}),
    });

    const payload = response.status === 204 ? null : await response.json().catch(() => null);

    if (!response.ok) {
        const error = new Error(payload?.message ?? 'Permintaan tidak dapat diproses.');
        error.status = response.status;
        error.fields = payload?.errors ?? {};
        throw error;
    }

    return payload;
}

function Icon({ name, size = 18 }) {
    const paths = {
        search: <><circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" /></>,
        plus: <><path d="M12 5v14M5 12h14" /></>,
        edit: <><path d="M12 20h9" /><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z" /></>,
        trash: <><path d="M3 6h18M8 6V4h8v2m3 0-1 14H6L5 6m4 4v6m6-6v6" /></>,
        logout: <><path d="M10 17l5-5-5-5M15 12H3" /><path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7" /></>,
        people: <><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="10" cy="7" r="4" /><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" /></>,
        location: <><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z" /><circle cx="12" cy="10" r="2.5" /></>,
        team: <><rect x="3" y="4" width="18" height="16" rx="3" /><path d="M8 9h8M8 13h5" /></>,
        arrow: <><path d="M5 12h14M13 6l6 6-6 6" /></>,
        close: <><path d="m18 6-12 12M6 6l12 12" /></>,
    };

    return (
        <svg width={size} height={size} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
            {paths[name]}
        </svg>
    );
}

function AppleMark({ size = 28 }) {
    return (
        <svg width={size} height={size} viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M17.05 12.54c.02 2.07 1.82 2.76 1.84 2.77-.02.05-.29 1.01-.95 2-.57.85-1.16 1.69-2.09 1.71-.91.02-1.21-.55-2.26-.55-1.05 0-1.38.53-2.24.57-.9.03-1.59-.92-2.16-1.77-1.17-1.7-2.06-4.8-.86-6.9.6-1.04 1.67-1.7 2.84-1.72.89-.02 1.73.6 2.28.6.55 0 1.57-.74 2.64-.63.45.02 1.72.18 2.53 1.37-.07.05-1.51.88-1.57 2.55ZM15.32 6.62c.48-.58.81-1.39.72-2.2-.69.03-1.52.46-2.01 1.04-.44.5-.83 1.32-.73 2.1.77.06 1.55-.39 2.02-.94Z" />
        </svg>
    );
}

function initials(name = '') {
    return name.split(' ').filter(Boolean).slice(0, 2).map((part) => part[0]).join('').toUpperCase() || 'HR';
}

function formatSalary(value) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(Number(value));
}

function LoginScreen({ onLogin }) {
    const [email, setEmail] = useState('admin@apple.com');
    const [password, setPassword] = useState('password123');
    const [error, setError] = useState('');
    const [submitting, setSubmitting] = useState(false);

    async function handleSubmit(event) {
        event.preventDefault();
        setError('');
        setSubmitting(true);

        try {
            await onLogin({ email, password });
        } catch (loginError) {
            setError(loginError.message);
        } finally {
            setSubmitting(false);
        }
    }

    return (
        <main className="hr-login-page">
            <div className="hr-login-topline"><span><AppleMark size={22} /> Apple Directory</span><span>PEOPLE & CULTURE</span></div>
            <section className="hr-login-layout">
                <div className="hr-login-story">
                    <div className="hr-eyebrow"><span className="hr-eyebrow-dot" /> HUMAN RESOURCES</div>
                    <h1>Great work<br />starts with<br /><em>great people.</em></h1>
                    <p>A clear view of the people, teams, and talent shaping what comes next.</p>
                    <div className="hr-login-footnote">A people-first directory for Apple teams.</div>
                </div>

                <form className="hr-login-card" onSubmit={handleSubmit}>
                    <div className="hr-login-card-heading">
                        <div className="hr-lock-icon"><span /></div>
                        <p className="hr-eyebrow">SECURE SIGN IN</p>
                        <h2>Welcome back.</h2>
                        <p className="hr-muted">Sign in to continue to your directory.</p>
                    </div>
                    {error && <div className="hr-alert" role="alert">{error}</div>}
                    <label className="hr-label" htmlFor="email">Email address</label>
                    <input className="hr-input" id="email" type="email" autoComplete="username" value={email} onChange={(event) => setEmail(event.target.value)} required />
                    <label className="hr-label" htmlFor="password">Password</label>
                    <input className="hr-input" id="password" type="password" autoComplete="current-password" value={password} onChange={(event) => setPassword(event.target.value)} required />
                    <button className="hr-button hr-button-dark hr-login-button" type="submit" disabled={submitting}>
                        {submitting ? 'Signing in…' : 'Continue'} <Icon name="arrow" size={17} />
                    </button>
                    <div className="hr-demo-accounts">
                        <span>DEMO ACCOUNTS</span>
                        <button type="button" onClick={() => { setEmail('admin@apple.com'); setPassword('password123'); }}>Admin</button>
                        <button type="button" onClick={() => { setEmail('user@apple.com'); setPassword('password123'); }}>User</button>
                    </div>
                </form>
            </section>
            <footer className="hr-login-footer"><span>© Apple Directory</span><span>Built for people. Designed for clarity.</span></footer>
        </main>
    );
}

const emptyEmployee = {
    employee_id: '',
    nama: '',
    role_title: '',
    team_division: '',
    location: '',
    salary: '',
};

function EmployeeDialog({ employee, token, onClose, onSaved }) {
    const [values, setValues] = useState(employee ? { ...employee, salary: String(employee.salary) } : emptyEmployee);
    const [errors, setErrors] = useState({});
    const [submitting, setSubmitting] = useState(false);
    const isEditing = Boolean(employee);

    function updateField(event) {
        setValues((current) => ({ ...current, [event.target.name]: event.target.value }));
        setErrors((current) => ({ ...current, [event.target.name]: undefined, form: undefined }));
    }

    async function handleSubmit(event) {
        event.preventDefault();
        setSubmitting(true);
        setErrors({});

        try {
            const path = isEditing ? `/employees/${employee.id}` : '/employees';
            await apiRequest(path, {
                token,
                method: isEditing ? 'PUT' : 'POST',
                body: { ...values, salary: Number(values.salary) },
            });
            await onSaved(isEditing ? 'Employee record updated.' : 'Employee added to the directory.');
            onClose();
        } catch (saveError) {
            setErrors({ ...saveError.fields, form: saveError.fields && Object.keys(saveError.fields).length ? '' : saveError.message });
        } finally {
            setSubmitting(false);
        }
    }

    const fields = [
        ['employee_id', 'Employee ID', 'AAPL-103'],
        ['nama', 'Full name', 'Employee name'],
        ['role_title', 'Job title', 'Product Designer'],
        ['team_division', 'Team / division', 'Design'],
        ['location', 'Location', 'Apple Park, Cupertino'],
        ['salary', 'Annual salary (USD)', '120000'],
    ];

    return (
        <div className="hr-dialog-backdrop" role="presentation" onMouseDown={(event) => { if (event.target === event.currentTarget) onClose(); }}>
            <section className="hr-dialog" role="dialog" aria-modal="true" aria-labelledby="employee-dialog-title">
                <header className="hr-dialog-header">
                    <div><p className="hr-eyebrow">EMPLOYEE RECORD</p><h2 id="employee-dialog-title">{isEditing ? 'Edit employee' : 'Add a person'}</h2></div>
                    <button className="hr-icon-button" type="button" onClick={onClose} aria-label="Close dialog"><Icon name="close" /></button>
                </header>
                <form onSubmit={handleSubmit}>
                    {errors.form && <div className="hr-alert" role="alert">{errors.form}</div>}
                    <div className="hr-form-grid">
                        {fields.map(([name, label, placeholder]) => (
                            <label className={`hr-label ${name === 'location' ? 'hr-span-two' : ''}`} key={name}>
                                {label}
                                <input
                                    className={`hr-input ${errors[name] ? 'hr-input-error' : ''}`}
                                    name={name}
                                    type={name === 'salary' ? 'number' : 'text'}
                                    min={name === 'salary' ? '0' : undefined}
                                    step={name === 'salary' ? '1' : undefined}
                                    placeholder={placeholder}
                                    value={values[name] ?? ''}
                                    onChange={updateField}
                                    required
                                />
                                {errors[name]?.[0] && <span className="hr-field-error">{errors[name][0]}</span>}
                            </label>
                        ))}
                    </div>
                    <div className="hr-dialog-actions">
                        <button className="hr-button hr-button-light" type="button" onClick={onClose}>Cancel</button>
                        <button className="hr-button hr-button-dark" type="submit" disabled={submitting}>{submitting ? 'Saving…' : isEditing ? 'Save changes' : 'Add employee'}</button>
                    </div>
                </form>
            </section>
        </div>
    );
}

function Dashboard({ user, token, employees, loading, onLogout, onRefresh }) {
    const [search, setSearch] = useState('');
    const [dialogEmployee, setDialogEmployee] = useState(undefined);
    const [toast, setToast] = useState('');
    const isAdmin = user.role === 'admin';

    const divisions = useMemo(() => new Set(employees.map((employee) => employee.team_division)).size, [employees]);
    const visibleEmployees = useMemo(() => {
        const normalizedSearch = search.trim().toLowerCase();
        if (!normalizedSearch) return employees;

        return employees.filter((employee) => [
            employee.employee_id,
            employee.nama,
            employee.role_title,
            employee.team_division,
            employee.location,
        ].some((value) => value.toLowerCase().includes(normalizedSearch)));
    }, [employees, search]);

    useEffect(() => {
        if (!toast) return undefined;
        const timeout = window.setTimeout(() => setToast(''), 3200);
        return () => window.clearTimeout(timeout);
    }, [toast]);

    async function deleteEmployee(employee) {
        if (!window.confirm(`Delete ${employee.nama} from the directory?`)) return;

        try {
            await apiRequest(`/employees/${employee.id}`, { token, method: 'DELETE' });
            setToast(`${employee.nama} was removed from the directory.`);
            await onRefresh();
        } catch (error) {
            setToast(error.message);
        }
    }

    return (
        <div className="hr-app-shell">
            <header className="hr-topbar">
                <div className="hr-topbar-inner">
                    <a className="hr-brand" href="/" aria-label="Apple Directory home"><AppleMark /><span>Apple Directory</span><i>HR</i></a>
                    <div className="hr-topbar-right">
                        <div className="hr-user-chip"><div className="hr-avatar hr-avatar-small">{initials(user.name)}</div><div><strong>{user.name}</strong><span>{isAdmin ? 'Administrator' : 'Team member'}</span></div></div>
                        <button className="hr-icon-button hr-logout" type="button" onClick={onLogout} aria-label="Sign out" title="Sign out"><Icon name="logout" /></button>
                    </div>
                </div>
            </header>

            <main className="hr-main">
                <div className="hr-welcome-row">
                    <div>
                        <p className="hr-eyebrow">PEOPLE & CULTURE <span className="hr-eyebrow-divider">/</span> DIRECTORY</p>
                        <h1>People <span className="hr-title-count">{String(employees.length).padStart(2, '0')}</span></h1>
                        <p className="hr-muted">The people behind the products, ideas, and experiences.</p>
                    </div>
                    {isAdmin && <button className="hr-button hr-button-dark" type="button" onClick={() => setDialogEmployee(null)}><Icon name="plus" size={17} /> Add employee</button>}
                </div>

                <section className="hr-metrics" aria-label="Directory summary">
                    <article className="hr-metric-card hr-metric-dark"><span className="hr-metric-label">IN THE DIRECTORY</span><div className="hr-metric-bottom"><strong>{employees.length.toString().padStart(2, '0')}</strong><span><Icon name="people" size={19} /></span></div><p>People making a difference</p></article>
                    <article className="hr-metric-card"><span className="hr-metric-label">TEAMS & DIVISIONS</span><div className="hr-metric-bottom"><strong>{divisions.toString().padStart(2, '0')}</strong><span><Icon name="team" size={19} /></span></div><p>Working together across Apple</p></article>
                    <article className="hr-metric-card"><span className="hr-metric-label">YOUR ACCESS</span><div className="hr-metric-access"><span className={`hr-role-dot ${isAdmin ? 'hr-role-admin' : ''}`} />{isAdmin ? 'Full access' : 'Read only'}</div><p>{isAdmin ? 'Create, edit, and manage records' : 'Browse employee profiles'}</p></article>
                </section>

                <section className="hr-directory-card">
                    <div className="hr-directory-heading">
                        <div><p className="hr-eyebrow">TEAM DIRECTORY</p><h2>All employees</h2><p className="hr-muted">A thoughtful view of the talent across our teams.</p></div>
                        <label className="hr-search"><Icon name="search" size={17} /><input type="search" placeholder="Search people, teams…" value={search} onChange={(event) => setSearch(event.target.value)} /><kbd>⌘ K</kbd></label>
                    </div>
                    <div className="hr-table-wrap">
                        <table className="hr-table">
                            <thead><tr><th>NAME</th><th>ROLE</th><th>TEAM</th><th>LOCATION</th><th>ANNUAL SALARY</th>{isAdmin && <th aria-label="Actions" />}</tr></thead>
                            <tbody>
                                {loading ? <tr><td className="hr-table-state" colSpan={isAdmin ? 6 : 5}>Loading your directory…</td></tr> : visibleEmployees.length === 0 ? <tr><td className="hr-table-state" colSpan={isAdmin ? 6 : 5}>{search ? 'No employees match your search.' : 'No employee records yet.'}</td></tr> : visibleEmployees.map((employee, index) => (
                                    <tr key={employee.id}>
                                        <td><div className="hr-person"><div className={`hr-avatar hr-avatar-tone-${index % 4}`}>{initials(employee.nama)}</div><div><strong>{employee.nama}</strong><span>{employee.employee_id}</span></div></div></td>
                                        <td><span className="hr-role-title">{employee.role_title}</span></td>
                                        <td><span className="hr-team-label">{employee.team_division}</span></td>
                                        <td><span className="hr-location"><Icon name="location" size={14} />{employee.location}</span></td>
                                        <td><span className="hr-salary">{formatSalary(employee.salary)}</span></td>
                                        {isAdmin && <td><div className="hr-row-actions"><button type="button" className="hr-icon-button" aria-label={`Edit ${employee.nama}`} title="Edit employee" onClick={() => setDialogEmployee(employee)}><Icon name="edit" size={16} /></button><button type="button" className="hr-icon-button hr-delete-button" aria-label={`Delete ${employee.nama}`} title="Delete employee" onClick={() => deleteEmployee(employee)}><Icon name="trash" size={16} /></button></div></td>}
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <div className="hr-table-footer"><span>Showing <strong>{visibleEmployees.length}</strong> of <strong>{employees.length}</strong> people</span><span className="hr-updated"><span /> Directory is up to date</span></div>
                </section>
                <footer className="hr-app-footer"><span>Apple Directory · Human Resources</span><span>{isAdmin ? 'Administrator access' : 'Read-only access'}</span></footer>
            </main>
            {dialogEmployee !== undefined && <EmployeeDialog employee={dialogEmployee} token={token} onClose={() => setDialogEmployee(undefined)} onSaved={async (message) => { await onRefresh(); setToast(message); }} />}
            {toast && <div className="hr-toast" role="status">{toast}</div>}
        </div>
    );
}

function App() {
    const [token, setToken] = useState(() => window.sessionStorage.getItem(TOKEN_KEY));
    const [user, setUser] = useState(null);
    const [employees, setEmployees] = useState([]);
    const [loading, setLoading] = useState(false);
    const [checkingSession, setCheckingSession] = useState(Boolean(window.sessionStorage.getItem(TOKEN_KEY)));

    const refreshEmployees = useCallback(async (activeToken = token) => {
        if (!activeToken) return;
        setLoading(true);
        try {
            const result = await apiRequest('/employees', { token: activeToken });
            setEmployees(result.data ?? []);
        } finally {
            setLoading(false);
        }
    }, [token]);

    useEffect(() => {
        if (!token) {
            setCheckingSession(false);
            setUser(null);
            return undefined;
        }

        let cancelled = false;
        setCheckingSession(true);

        Promise.all([
            apiRequest('/auth/me', { token }),
            apiRequest('/employees', { token }),
        ]).then(([profile, directory]) => {
            if (cancelled) return;
            setUser(profile.data);
            setEmployees(directory.data ?? []);
        }).catch(() => {
            if (cancelled) return;
            window.sessionStorage.removeItem(TOKEN_KEY);
            setToken(null);
            setUser(null);
        }).finally(() => {
            if (!cancelled) setCheckingSession(false);
        });

        return () => { cancelled = true; };
    }, [token]);

    async function login(credentials) {
        const result = await apiRequest('/auth/login', { method: 'POST', body: credentials });
        window.sessionStorage.setItem(TOKEN_KEY, result.token);
        setUser(result.user);
        setToken(result.token);
    }

    async function logout() {
        try {
            await apiRequest('/auth/logout', { token, method: 'POST' });
        } catch {
            // Clear the local session even if the token has already expired.
        }
        window.sessionStorage.removeItem(TOKEN_KEY);
        setToken(null);
        setUser(null);
        setEmployees([]);
    }

    if (checkingSession) {
        return <main className="hr-loading-screen"><AppleMark size={30} /><span>Opening your directory…</span></main>;
    }

    if (!user || !token) {
        return <LoginScreen onLogin={login} />;
    }

    return <Dashboard user={user} token={token} employees={employees} loading={loading} onLogout={logout} onRefresh={refreshEmployees} />;
}

createRoot(document.getElementById('app')).render(<App />);

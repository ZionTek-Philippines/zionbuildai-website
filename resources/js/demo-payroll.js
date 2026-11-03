const peso = (amount) => `₱${Number(amount).toLocaleString('en-PH')}`;

const PRESENT = 'rounded-lg py-1.5 text-center text-[11px] font-semibold bg-emerald-50 text-demo-green';
const ABSENT = 'rounded-lg py-1.5 text-center text-[11px] font-semibold bg-red-50 text-red-500 line-through';

// Payroll: each worker's "Payslip" opens a side drawer built from that row's numbers.
export function initPayroll() {
    const drawer = document.querySelector('[data-payslip-drawer]');

    if (!drawer) {
        return;
    }

    const field = (name) => drawer.querySelector(`[data-slip-${name}]`);
    const close = () => drawer.classList.replace('flex', 'hidden');

    document.querySelectorAll('[data-payslip]').forEach((button) => {
        button.addEventListener('click', () => {
            const worker = JSON.parse(button.dataset.payslip);

            field('name').textContent = worker.name;
            field('role').textContent = worker.role;
            field('days').textContent = worker.days;
            field('rate').textContent = peso(worker.rate);
            field('gross').textContent = peso(worker.gross);
            for (const [name, amount] of [['vale', worker.vale], ['advance', worker.advance]]) {
                field(name).textContent = amount ? `− ${peso(amount)}` : '—';
                field(name).classList.toggle('text-slate-300', !amount);
                field(name).classList.toggle('text-amber-600', Boolean(amount));
            }
            field('net').textContent = peso(worker.net);
            drawer.querySelectorAll('[data-slip-day]').forEach((day) => {
                day.className = Number(day.dataset.slipDay) === worker.absent ? ABSENT : PRESENT;
            });

            drawer.classList.replace('hidden', 'flex');
        });
    });

    field('close').addEventListener('click', close);
    drawer.addEventListener('click', (event) => event.target === drawer && close());
    document.addEventListener('keydown', (event) => event.key === 'Escape' && close());
}

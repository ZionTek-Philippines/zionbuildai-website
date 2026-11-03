import { showDemoToast } from './demo-field-reports';

const peso = (amount) => `₱${Number(amount).toLocaleString('en-PH')}`;

const statusChip = (paid) => {
    const chip = document.createElement('span');
    chip.className = paid
        ? 'rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-demo-green'
        : 'rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[11px] font-bold text-amber-800';
    chip.textContent = paid ? 'Paid' : 'Unpaid';

    return chip;
};

// Finances: clicking an expense row opens its details in a side drawer.
export function initFinances() {
    const drawer = document.querySelector('[data-expense-drawer]');

    if (!drawer) {
        return;
    }

    const field = (name) => drawer.querySelector(`[data-drawer-${name}]`);
    const payButton = field('pay');
    let currentRow = null;

    const close = () => drawer.classList.replace('flex', 'hidden');

    function open(row) {
        const expense = JSON.parse(row.dataset.expense);
        currentRow = row;

        field('ref').textContent = expense.ref;
        field('status').replaceChildren(statusChip(expense.paid));
        field('description').textContent = expense.description;
        field('date').textContent = expense.date;
        field('category').textContent = expense.category;
        field('vendor').textContent = expense.vendor;
        field('by').textContent = expense.by;
        field('total').textContent = peso(expense.amount);
        field('items').replaceChildren(...expense.items.map(([name, quantity, amount]) => {
            const item = document.createElement('li');
            item.className = 'flex items-start justify-between gap-3';
            item.innerHTML = '<span><span class="block font-semibold text-slate-800"></span><span class="text-slate-500"></span></span><span class="font-demo font-bold text-slate-800"></span>';
            item.querySelector('.block').textContent = name;
            item.querySelector('.text-slate-500').textContent = quantity;
            item.lastElementChild.textContent = peso(amount);

            return item;
        }));
        payButton.classList.toggle('hidden', expense.paid);

        drawer.classList.replace('hidden', 'flex');
    }

    document.querySelectorAll('[data-expense]').forEach((row) => {
        row.addEventListener('click', () => open(row));
        row.addEventListener('keydown', (event) => event.key === 'Enter' && open(row));
    });

    payButton.addEventListener('click', () => {
        const expense = JSON.parse(currentRow.dataset.expense);
        expense.paid = true;
        currentRow.dataset.expense = JSON.stringify(expense);
        currentRow.querySelector('[data-expense-status]').replaceChildren(statusChip(true));
        close();

        showDemoToast({
            en: `${expense.ref} marked as paid. In the real app, ${peso(expense.amount)} moves from unpaid to spent and shows in the bank statement.`,
            tl: `Bayad na ang ${expense.ref}. Sa totoong app, lilipat ang ${peso(expense.amount)} mula unpaid papuntang spent at lalabas sa bank statement.`,
        });
    });

    drawer.querySelector('[data-drawer-close]').addEventListener('click', close);
    drawer.addEventListener('click', (event) => event.target === drawer && close());
    document.addEventListener('keydown', (event) => event.key === 'Escape' && close());
}

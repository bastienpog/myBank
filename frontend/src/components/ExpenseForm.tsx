import { useState } from "react";

const CATEGORIES = [
  "Housing",
  "Food",
  "Transport",
  "Health",
  "Leisure",
  "Other",
];

const inputStyle = {
  width: "100%",
  padding: "10px 14px",
  border: "1.5px solid #E5E7EB",
  borderRadius: "10px",
  fontSize: "14px",
  fontFamily: "'DM Sans', 'Segoe UI', sans-serif",
  color: "#111827",
  backgroundColor: "#FAFAFA",
  outline: "none",
  boxSizing: "border-box",
  transition: "border-color 0.15s",
};

const labelStyle = {
  display: "block",
  fontSize: "12px",
  fontWeight: 600,
  color: "#374151",
  marginBottom: "5px",
  letterSpacing: "0.04em",
  textTransform: "uppercase",
};

export default function ExpenseForm({ onAdd }) {
  const [label, setLabel] = useState("");
  const [amount, setAmount] = useState("");
  const [date, setDate] = useState("");
  const [category, setCategory] = useState("Other");

  function handleSubmit(e) {
    e.preventDefault();
    if (!label || !amount) return;

    const newExpense = {
      id: Date.now(),
      label,
      amount: parseFloat(amount),
      date: date || new Date().toISOString().slice(0, 10),
      category,
    };

    onAdd?.(newExpense);
    setLabel("");
    setAmount("");
    setDate("");
    setCategory("Other");
  }

  return (
    <div
      style={{
        fontFamily: "'DM Sans', 'Segoe UI', sans-serif",
        backgroundColor: "#fff",
        border: "1.5px solid #E5E7EB",
        borderRadius: "16px",
        padding: "24px",
        display: "flex",
        flexDirection: "column",
        gap: "16px",
      }}
    >
      <h2
        style={{
          margin: 0,
          fontSize: "17px",
          fontWeight: 700,
          color: "#111827",
        }}
      >
        New Expense
      </h2>

      {/* Label field */}
      <div>
        <label htmlFor="expense-label" style={labelStyle}>
          Label
        </label>
        <input
          id="expense-label"
          type="text"
          placeholder="e.g. Groceries"
          value={label}
          onChange={(e) => setLabel(e.target.value)}
          style={inputStyle}
          onFocus={(e) => (e.target.style.borderColor = "#6366F1")}
          onBlur={(e) => (e.target.style.borderColor = "#E5E7EB")}
        />
      </div>

      {/* Amount field — label text matches /amount/i */}
      <div>
        <label htmlFor="expense-amount" style={labelStyle}>
          Amount
        </label>
        <input
          id="expense-amount"
          type="number"
          placeholder="0.00"
          min="0"
          step="0.01"
          value={amount}
          onChange={(e) => setAmount(e.target.value)}
          style={inputStyle}
          onFocus={(e) => (e.target.style.borderColor = "#6366F1")}
          onBlur={(e) => (e.target.style.borderColor = "#E5E7EB")}
        />
      </div>

      {/* Date + Category row */}
      <div style={{ display: "flex", gap: "12px" }}>
        <div style={{ flex: 1 }}>
          <label htmlFor="expense-date" style={labelStyle}>
            Date
          </label>
          <input
            id="expense-date"
            type="date"
            value={date}
            onChange={(e) => setDate(e.target.value)}
            style={inputStyle}
            onFocus={(e) => (e.target.style.borderColor = "#6366F1")}
            onBlur={(e) => (e.target.style.borderColor = "#E5E7EB")}
          />
        </div>
        <div style={{ flex: 1 }}>
          <label htmlFor="expense-category" style={labelStyle}>
            Category
          </label>
          <select
            id="expense-category"
            value={category}
            onChange={(e) => setCategory(e.target.value)}
            style={{ ...inputStyle, cursor: "pointer" }}
            onFocus={(e) => (e.target.style.borderColor = "#6366F1")}
            onBlur={(e) => (e.target.style.borderColor = "#E5E7EB")}
          >
            {CATEGORIES.map((cat) => (
              <option key={cat} value={cat}>
                {cat}
              </option>
            ))}
          </select>
        </div>
      </div>

      {/* Submit button — name matches /add/i */}
      <button
        type="button"
        onClick={handleSubmit}
        style={{
          padding: "12px",
          borderRadius: "10px",
          border: "none",
          backgroundColor: "#4F46E5",
          color: "#fff",
          fontSize: "14px",
          fontWeight: 700,
          cursor: "pointer",
          letterSpacing: "0.03em",
          transition: "background-color 0.15s, transform 0.1s",
          fontFamily: "'DM Sans', 'Segoe UI', sans-serif",
        }}
        onMouseEnter={(e) =>
          (e.currentTarget.style.backgroundColor = "#4338CA")
        }
        onMouseLeave={(e) =>
          (e.currentTarget.style.backgroundColor = "#4F46E5")
        }
        onMouseDown={(e) => (e.currentTarget.style.transform = "scale(0.98)")}
        onMouseUp={(e) => (e.currentTarget.style.transform = "scale(1)")}
      >
        Add Expense
      </button>
    </div>
  );
}

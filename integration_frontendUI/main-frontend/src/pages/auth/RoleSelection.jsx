import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import {
  HiChevronDown,
  HiClipboardList,
  HiAcademicCap,
  HiCalendar,
  HiUserGroup,
} from "react-icons/hi";
import logo from "../../assets/logo.png";

const REGISTRATION_URL = process.env.REACT_APP_REGISTRATION_URL || "http://localhost/registration_system/public";
const SCHEDULING_URL   = process.env.REACT_APP_SCHEDULING_URL   || "http://localhost/scheduling_system";
const ADMISSION_URL    = process.env.REACT_APP_ADMISSION_URL    || "http://localhost/admission_system/public";

/* ─── reusable primary button ─────────────────────────────────────── */
function PrimaryBtn({ label, onClick, rightEl }) {
  const base = {
    background: "rgba(255,255,255,0.14)",
    border: "1px solid rgba(255,255,255,0.22)",
  };
  return (
    <button
      onClick={onClick}
      className="w-full py-[14px] rounded-2xl font-semibold text-[15px] text-white
                 tracking-wide text-center relative
                 transition-all duration-200 active:scale-[0.97]"
      style={base}
      onMouseEnter={(e) => {
        e.currentTarget.style.background = "rgba(255,255,255,0.23)";
        e.currentTarget.style.border = "1px solid rgba(255,255,255,0.40)";
      }}
      onMouseLeave={(e) => {
        e.currentTarget.style.background = base.background;
        e.currentTarget.style.border = base.border;
      }}
    >
      {label}
      {rightEl && (
        <span className="absolute right-4 top-1/2 -translate-y-1/2">{rightEl}</span>
      )}
    </button>
  );
}

/* ─── secondary sub-button ────────────────────────────────────────── */
function SubBtn({ icon: Icon, label, onClick }) {
  const base = {
    background: "rgba(255,255,255,0.08)",
    border: "1px solid rgba(255,255,255,0.15)",
  };
  return (
    <button
      onClick={onClick}
      className="w-full py-[10px] rounded-xl font-medium text-[13px] text-white/90 tracking-wide
                 flex items-center gap-3 px-4
                 transition-all duration-200 active:scale-[0.97]"
      style={base}
      onMouseEnter={(e) => {
        e.currentTarget.style.background = "rgba(255,255,255,0.16)";
        e.currentTarget.style.border = "1px solid rgba(255,255,255,0.32)";
      }}
      onMouseLeave={(e) => {
        e.currentTarget.style.background = base.background;
        e.currentTarget.style.border = base.border;
      }}
    >
      {Icon && (
        <span className="flex items-center justify-center w-7 h-7 rounded-lg shrink-0"
              style={{ background: "rgba(255,255,255,0.12)" }}>
          <Icon className="w-4 h-4" />
        </span>
      )}
      {label}
    </button>
  );
}

/* ─── expandable group ─────────────────────────────────────────────── */
function ExpandableBtn({ label, open, onToggle, children }) {
  return (
    <div className="flex flex-col gap-1.5">
      <PrimaryBtn
        label={label}
        onClick={onToggle}
        rightEl={
          <HiChevronDown
            className="w-4 h-4 opacity-60 shrink-0 transition-transform duration-300"
            style={{ transform: open ? "rotate(180deg)" : "rotate(0deg)" }}
          />
        }
      />
      {open && (
        <div
          className="flex flex-col gap-1.5 pl-3 pt-0.5 border-l-2 ml-3"
          style={{ borderColor: "rgba(255,255,255,0.18)" }}
        >
          {children}
        </div>
      )}
    </div>
  );
}

/* ─── main component ──────────────────────────────────────────────── */
export default function RoleSelection() {
  const navigate = useNavigate();
  const [adminOpen,      setAdminOpen]      = useState(false);
  const [instructorOpen, setInstructorOpen] = useState(false);
  const [studentOpen,    setStudentOpen]    = useState(false);

  useEffect(() => {
    fetch(`${REGISTRATION_URL}/auto-logout`, { credentials: "include" }).catch(() => {});
    fetch(`${ADMISSION_URL}/auto-logout`, { credentials: "include" }).catch(() => {});
  }, []);

  return (
    <div className="relative h-screen w-screen overflow-hidden">

      {/* Background video */}
      <video
        autoPlay loop muted playsInline
        controlsList="nodownload"
        onContextMenu={(e) => e.preventDefault()}
        style={{ pointerEvents: "none" }}
        className="absolute inset-0 w-full h-full object-cover"
      >
        <source src="/videos/parsu-sunrise720.mp4" type="video/mp4" />
      </video>

      <div className="absolute inset-0 bg-gradient-to-br from-black/70 via-black/50 to-black/40" />

      {/* Content */}
      <div className="relative z-10 flex h-full items-center justify-center px-4">
        <div className="w-full max-w-[340px] flex flex-col items-center">

          {/* Logo */}
          <img
            src={logo}
            alt="ParSU Seal"
            className="w-[110px] h-[110px] object-contain mb-4"
            style={{ filter: "drop-shadow(0 4px 18px rgba(0,0,0,0.85))" }}
          />

          <h1
            className="text-[22px] font-bold text-white text-center leading-snug tracking-tight"
            style={{ textShadow: "0 2px 12px rgba(0,0,0,0.9)" }}
          >
            Partido State University
          </h1>
          <p
            className="text-[13px] text-white/70 mt-1 mb-8 text-center"
            style={{ textShadow: "0 1px 6px rgba(0,0,0,0.9)" }}
          >
            Goa, Camarines Sur
          </p>

          {/* ── Buttons ── */}
          <div className="w-full flex flex-col gap-3">

            {/* Administrator */}
            <ExpandableBtn
              label="Administrator"
              open={adminOpen}
              onToggle={() => setAdminOpen(!adminOpen)}
            >
              <SubBtn
                icon={HiClipboardList}
                label="Admission System"
                onClick={() => navigate("/admin-portal")}
              />
              <SubBtn
                icon={HiAcademicCap}
                label="Registration System"
                onClick={() => { window.location.href = `${REGISTRATION_URL}/?role=admin`; }}
              />
              <SubBtn
                icon={HiCalendar}
                label="Scheduling System"
                onClick={() => { window.location.href = `${SCHEDULING_URL}/administrator_login.html?v=${Date.now()}`; }}
              />
            </ExpandableBtn>

            {/* Instructor */}
            <ExpandableBtn
              label="Instructor"
              open={instructorOpen}
              onToggle={() => setInstructorOpen(!instructorOpen)}
            >
              <SubBtn
                icon={HiUserGroup}
                label="Registration System"
                onClick={() => { window.location.href = `${REGISTRATION_URL}/?role=teacher`; }}
              />
              <SubBtn
                icon={HiCalendar}
                label="Scheduling System"
                onClick={() => { window.location.href = `${SCHEDULING_URL}/instructor_login.html?v=${Date.now()}`; }}
              />
            </ExpandableBtn>

            {/* Student */}
            <ExpandableBtn
              label="Student"
              open={studentOpen}
              onToggle={() => setStudentOpen(!studentOpen)}
            >
              <SubBtn
                icon={HiAcademicCap}
                label="Registration System"
                onClick={() => { window.location.href = `${REGISTRATION_URL}/?role=student`; }}
              />
              <SubBtn
                icon={HiCalendar}
                label="Scheduling System"
                onClick={() => { window.location.href = `${SCHEDULING_URL}/student_login.html?v=${Date.now()}`; }}
              />
            </ExpandableBtn>

            {/* Apply for Admission */}
            <button
              onClick={() => navigate("/admission-form")}
              className="w-full py-[14px] rounded-2xl font-semibold text-[15px] text-white
                         tracking-wide mt-1 transition-all duration-200 active:scale-[0.97]"
              style={{
                background: "rgba(99,102,241,0.78)",
                border: "1px solid rgba(129,132,255,0.45)",
                boxShadow: "0 4px 24px rgba(99,102,241,0.40)",
              }}
              onMouseEnter={(e) => { e.currentTarget.style.background = "rgba(99,102,241,0.95)"; }}
              onMouseLeave={(e) => { e.currentTarget.style.background = "rgba(99,102,241,0.78)"; }}
            >
              Apply for Admission
            </button>

          </div>
        </div>
      </div>

      {/* Bottom tagline */}
      <div className="absolute bottom-6 left-0 right-0 text-center z-10">
        <p
          className="text-white/60 text-[11px] tracking-[0.2em] uppercase font-medium"
          style={{ textShadow: "0 1px 6px rgba(0,0,0,0.9)" }}
        >
          Mus Nak'ta Mga Partidoanon
        </p>
      </div>

    </div>
  );
}

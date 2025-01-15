import React from "react";

const SelectDropdown = ({ options, className, ...props }) => {
    return (
        <select
            {...props}
            className={
                "rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 " +
                className
            }
        >
            {options.map((el, index) => {
                return (
                    <option value={el} key={index}>
                        {el}
                    </option>
                );
            })}
        </select>
    );
};

export default SelectDropdown;

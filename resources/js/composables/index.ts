import {useUser} from './user';
import { saveAs } from "file-saver";
import * as XLSX from 'xlsx';


export const useComposables =()=>{

    return {
        useUser
    }   
}


 export function exportToExcel(columns:Array<any>, data:Array<any>, fileName:string = 'export.xlsx') {
    // Créer un tableau avec les labels comme première ligne
    const headers = columns.map(col => col.label);

    // Mapper les données en prenant les valeurs correspondantes aux keys
    // const rows = data.map(row =>
    //     columns.map(col => row[col.key] != null ? row[col.key] : '')
    // );

     const rows = data.map(row =>
        columns.map(col => {
        const value = row[col.key];

        if (Array.isArray(value)) {
            // Si c'est un tableau, on le transforme en string séparée par des virgules
                return value
                .map(item => {
                if (typeof item === 'object' && item !== null) {
                    // On peut choisir quels champs afficher, par ex name et path
                return Object.entries(item)
                .map(([k, v]) => `${k}: ${v}`)
                .join('; ');
                }
                return item;
                })
                .join(', ');
        } else if (value && typeof value === 'object') {
            // Si c'est un objet, on peut le transformer en JSON
            return JSON.stringify(value);
        } else if (value != null) {
            return value;
        } else {
            return '';
        }
        })
    );


    // Créer la feuille Excel avec header + lignes
    const worksheetData = [headers, ...rows];
    const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);

    worksheet['!cols'] = headers.map((h, i) => ({
    wch: Math.max(
        h.length,
        ...rows.map(r => (r[i] ? r[i].toString().length : 0))
    ) + 2 // un peu de marge
    }));

    // Créer le classeur et ajouter la feuille
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

    // Exporter le fichier
    XLSX.writeFile(workbook, fileName);
    }